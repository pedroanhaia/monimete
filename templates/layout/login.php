<!DOCTYPE html>
<html>
	<head>
		<?= $this->Html->charset() ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
<<<<<<< HEAD
     
=======
        <?= $this->Html->meta('icon','img/AGROCITY_LIVING_LABicon.jpg') ?>
>>>>>>> main
        <title> Monimete: Monitoramento metereológico - Agrocity </title>

		<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
		<?=
			$this->Html->css(['https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css','sidebars','home2' , 'classescssproprias','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css','styleSideBarComDarkMode.css', 'https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css'])
		?>

		<?php
			$this->Html->script(['https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js','https://code.jquery.com/jquery-3.6.0.min.js'])
		?>
		<?= $this->Html->css(['normalize.min', 'milligram.min', 'cake']) ?>

		<?= $this->fetch('meta') ?>
		<?= $this->fetch('css') ?>
		<?= $this->fetch('script') ?>
        <style>
            /* Adicione este estilo no seu CSS ou na tag style da página */
            .custom-logologin-size {
                max-width: 25%; /* Ajuste conforme necessário */
                height: auto;
                display: none;
            }
            .containerlogo {
                display: flex;
                align-content: flex-start;
                flex-direction: row;
                align-items: flex-start;
                justify-content: space-evenly;
                flex-wrap: wrap;
            }
            .containerText {
                flex: 1; /* Ajuste conforme necessário para controlar a altura das divs filhas */
                margin: 5px; /* Ajuste a margem conforme necessário */
            }
        </style>
	</head>
	<body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                            <div class = "containerTextLogin">
                                <h4 class="mb-0">Login</h4>
                            </div>
                            <div class = "containerlogo">
                            <?=$this->Html->image('AGROCITY_LIVING_LABicon.jpg', ['alt' => 'Agrocity Logo', 'width'=>'200em'])?>
                        </div>
                        <main class="main card-body">
                            <div class="container">
                                <?= $this->Flash->render() ?>
                                <?= $this->fetch('content') ?>
                            </div>
                        </main>
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>

