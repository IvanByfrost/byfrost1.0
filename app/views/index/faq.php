<?php
// Página de Preguntas Frecuentes
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h1>Preguntas Frecuentes (FAQ)</h1>
            <p>Aquí encontrarás respuestas a las preguntas más comunes sobre Byfrost.</p>
            
            <div class="accordion mt-4" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            ¿Qué es Byfrost?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Byfrost es una plataforma integral de gestión académica diseñada para instituciones educativas.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            ¿Cómo puedo registrarme?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Puedes registrarte haciendo clic en el botón "Registrarse" en la página principal.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            ¿Olvidé mi contraseña?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Puedes recuperar tu contraseña usando la opción "¿Olvidaste tu contraseña?" en la página de login.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 