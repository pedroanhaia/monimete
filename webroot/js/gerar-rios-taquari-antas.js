#!/usr/bin/env node

/**
 * Gera a camada dos principais rios da Bacia Taquari-Antas a partir da
 * Base Cartográfica do Estado do RS (SEMA/FEPAM), escala 1:25.000.
 *
 * Execute na pasta scripts:
 *   node gerar-rios-taquari-antas.js
 */

const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const MUNICIPALITIES_PATH = path.join(ROOT, 'geojs-43-mun.json');
const BASIN_PATH = path.join(ROOT, 'taquari-antas-municipios.json');
const OUTPUT_PATH = path.join(ROOT, 'rios-taquari-antas.geojson');
const SERVICE_URL =
    'https://services3.arcgis.com/txu3Aw836B0Ze9Ef/arcgis/rest/services/Hidrografia/FeatureServer/1/query';

const RIVER_NAMES = [
    'Rio Taquari',
    'Rio das Antas',
    'Rio Camisas',
    'Rio Tainhas',
    'Lajeado Grande',
    'Arroio São Marcos',
    'Rio Quebra-dentes',
    'Rio da Prata',
    'Rio Carreiro',
    'Rio Guaporé',
    'Rio Forqueta',
    'Rio Taquari-Mirim'
];

const DISPLAY_NAMES = {
    'Lajeado Grande': 'Rio Lajeado Grande',
    'Arroio São Marcos': 'Rio São Marcos',
    'Rio Quebra-dentes': 'Rio Quebra-Dentes'
};

function normalize(value) {
    return String(value || '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim()
        .toLowerCase();
}

function geometryPolygons(geometry) {
    if (!geometry) return [];
    if (geometry.type === 'Polygon') return [geometry.coordinates];
    if (geometry.type === 'MultiPolygon') return geometry.coordinates;
    return [];
}

function ringBounds(ring) {
    return ring.reduce(
        (bounds, point) => ({
            minX: Math.min(bounds.minX, point[0]),
            minY: Math.min(bounds.minY, point[1]),
            maxX: Math.max(bounds.maxX, point[0]),
            maxY: Math.max(bounds.maxY, point[1])
        }),
        { minX: Infinity, minY: Infinity, maxX: -Infinity, maxY: -Infinity }
    );
}

function pointInRing(point, ring) {
    let inside = false;
    const [x, y] = point;
    for (let i = 0, j = ring.length - 1; i < ring.length; j = i++) {
        const [xi, yi] = ring[i];
        const [xj, yj] = ring[j];
        const intersects =
            ((yi > y) !== (yj > y)) &&
            (x < ((xj - xi) * (y - yi)) / ((yj - yi) || Number.EPSILON) + xi);
        if (intersects) inside = !inside;
    }
    return inside;
}

function pointInPolygon(point, polygon) {
    if (!polygon[0] || !pointInRing(point, polygon[0])) return false;
    for (let i = 1; i < polygon.length; i++) {
        if (pointInRing(point, polygon[i])) return false;
    }
    return true;
}

function buildBasinPolygons(municipalityGeoJson, basinMetadata) {
    const basinNames = new Set(Object.keys(basinMetadata.municipios).map(normalize));
    const polygons = [];

    municipalityGeoJson.features.forEach(feature => {
        if (!basinNames.has(normalize(feature.properties && feature.properties.name))) return;
        geometryPolygons(feature.geometry).forEach(polygon => {
            if (polygon[0]) polygons.push({ polygon, bounds: ringBounds(polygon[0]) });
        });
    });
    return polygons;
}

function pointInsideBasin(point, polygons) {
    return polygons.some(item =>
        point[0] >= item.bounds.minX &&
        point[0] <= item.bounds.maxX &&
        point[1] >= item.bounds.minY &&
        point[1] <= item.bounds.maxY &&
        pointInPolygon(point, item.polygon)
    );
}

function lineTouchesBasin(coordinates, polygons) {
    if (!coordinates || !coordinates.length) return false;
    const samplingStep = Math.max(1, Math.floor(coordinates.length / 20));
    for (let i = 0; i < coordinates.length; i += samplingStep) {
        if (pointInsideBasin(coordinates[i], polygons)) return true;
    }
    return pointInsideBasin(coordinates[coordinates.length - 1], polygons);
}

async function fetchRiverSegments() {
    const quotedNames = RIVER_NAMES.map(name => `'${name.replace(/'/g, "''")}'`).join(',');
    const where = `nome IN (${quotedNames})`;
    const features = [];
    let offset = 0;

    while (true) {
        const params = new URLSearchParams({
            f: 'geojson',
            where,
            outFields: 'nome,nomeAbrev',
            returnGeometry: 'true',
            outSR: '4326',
            orderByFields: 'FID',
            resultOffset: String(offset),
            resultRecordCount: '2000'
        });
        const response = await fetch(`${SERVICE_URL}?${params}`);
        if (!response.ok) throw new Error(`ArcGIS respondeu HTTP ${response.status}`);
        const page = await response.json();
        if (!Array.isArray(page.features)) throw new Error('Resposta GeoJSON inválida');

        features.push(...page.features);
        if (!page.properties || !page.properties.exceededTransferLimit) break;
        offset += page.features.length;
    }
    return features;
}

async function main() {
    const municipalities = JSON.parse(fs.readFileSync(MUNICIPALITIES_PATH, 'utf8'));
    const basin = JSON.parse(fs.readFileSync(BASIN_PATH, 'utf8'));
    const basinPolygons = buildBasinPolygons(municipalities, basin);
    const sourceSegments = await fetchRiverSegments();
    const grouped = new Map();

    sourceSegments.forEach(feature => {
        if (!feature.geometry || feature.geometry.type !== 'LineString') return;
        if (!lineTouchesBasin(feature.geometry.coordinates, basinPolygons)) return;
        const sourceName = feature.properties && feature.properties.nome;
        const displayName = DISPLAY_NAMES[sourceName] || sourceName;
        if (!grouped.has(displayName)) grouped.set(displayName, []);
        grouped.get(displayName).push(feature.geometry.coordinates);
    });

    const output = {
        type: 'FeatureCollection',
        name: 'Principais rios da Bacia Hidrográfica Taquari-Antas',
        source: 'SEMA/FEPAM - Base Cartográfica do Estado do RS, escala 1:25.000',
        source_url: 'https://www.sema.rs.gov.br/si-dados-geoespaciais',
        generated_at: new Date().toISOString(),
        features: [...grouped.entries()]
            .sort(([a], [b]) => a.localeCompare(b, 'pt-BR'))
            .map(([name, coordinates]) => ({
                type: 'Feature',
                properties: { name },
                geometry: { type: 'MultiLineString', coordinates }
            }))
    };

    fs.writeFileSync(OUTPUT_PATH, JSON.stringify(output));
    console.log(`Gerado ${OUTPUT_PATH}`);
    console.log(`${output.features.length} rios, ${sourceSegments.length} trechos consultados`);
}

main().catch(error => {
    console.error(error);
    process.exitCode = 1;
});
