<?php
use app\assets\AppAsset;
use core\helpers\Url;
/* @var $this core\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<!Doctype html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title><?= $this->title ?></title>
        <?= $this->head() ?>
    </head>
    <body>
        <header>
            <nav>
                <a class="nav-brand" href="<?= Url::to(['/home/index']) ?>"><img src="<?= Framework::getAlias('@web/assets/img/site/logoBrand.jpg')?>" alt=""></a>
                <button class="dropdown-bars">Menu <span></span></button>

                <div class="navbar-collapse">
                    <ul>
                        <li>
                            <?php
                            if (Framework::$app->user->isGuest) {
                                ?>
                                <a class="nav-link" href="<?= Url::to(['/auth/login']) ?>">
                                    <i class="fas fa-user"></i>
                                    Login
                                </a>
                                <?php
                            }
                            else {
                                ?>
                                <a class="nav-link" href="<?= Url::to(['/auth/logout']) ?>">
                                    <i class="fas fa-user"></i>
                                    Logout
                                </a>
                                <?php
                            }
                            ?>
                        </li>
                        <li>
                            <a class="nav-link" href="<?= Url::to(['/admin/users-index']) ?>">
                                <i class="fas fa-user"></i>
                                admin users
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="<?= Url::to(['/hairdresser/index', '#' => 'contactez-nous']) ?>">
                                <i class="fas fa-mobile-alt"></i>
                                contactez-nous
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="<?= Url::to(['/hairdresser/index', '#' => 'nos-horaire']) ?>">
                                <i class="fas fa-clock"></i> 
                                Infos horaires
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="<?= Url::to(['/hairdresser/index']) ?>">
                                <i class="fas fa-store-alt"></i>
                                salon de coiffure
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="<?= Url::to(['/gallery/index']) ?>">
                                <i class="fas fa-images"></i>
                                lyon en photos
                            </a>
                        </li>
                    </ul>
                </div> <!-- end navbar collapse -->
            </nav> <!-- end nav -->
        </header>
        <div class="banner">
            <img src="<?= Framework::getAlias('@web/assets/img/site/banner.jpg') ?>" alt="">
        </div>
        <main>
            <?= $content ?>
        </main>
        <footer>
            <div class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-4 social-network py-1">
                            <div class="section-title">
                                <h5>Retrouvez nous egalement</h5>
                                <hr>
                            </div>
                            <ul>
                                <li><a class="btn btn-outline-brown rounded-circle mr-4" href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a class="btn btn-outline-brown rounded-circle mr-4" href="#"><i class="fab fa-instagram"></i></a></li>
                                <li><a class="btn btn-outline-brown rounded-circle mr-4" href="#"><i class="fab fa-telegram-plane"></i></a></li>
                                <li><a class="btn btn-outline-brown rounded-circle mr-4" href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            </ul>
                        </div> <!-- end col-4 -->
                        <div class="col-4  working-hours py-1">
                            <div class="section-title">
                                <h5>WORKING HOURS</h5>
                                <hr>
                            </div>
                            <ul>
                                <li>
                                    <span>Lundi</span>
                                    <small>12:30 - 19:30</small>
                                </li>
                                <li>
                                    <span>mardi</span>
                                    <small>09:30 - 19:30</small>
                                </li>
                                <li>
                                    <span>mercredi</span>
                                    <small>09:00 - 20:00</small>
                                </li>
                                <li>
                                    <span>jeudi</span>
                                    <small>09:30 - 19:30</small>
                                </li>
                                <li>
                                    <span>vendredi</span>
                                    <small>09:30 - 19:30</small>
                                </li>
                                <li>
                                    <span>samedi</span>
                                    <small>09:30 - 19:30</small>
                                </li>
                                <li>
                                    <span>dimenche</span>
                                    <small>Closed</small>
                                </li>
                            </ul>
                        </div> <!-- end col-3 -->
                        <div class="col-4 py-1 adresse">
                            <div class="section-title">
                                <h5>WORKING HOURS</h5>
                                <hr>
                            </div>
                            <p class="m-0">Dr.M BarberShop</p>
                            <p class="m-0">132 Avenue Thiers</p>
                            <p>69006 Lyon <a class="text-green p-0 small" href="https://goo.gl/maps/BQr3qvoczHfyrUUD8" target="_blank" rel="noopener noreferrer">ici</a> .</p>
                            <a href="tel:0983868415"class="btn btn-outline-brown" ><i class="fas fa-phone-alt"></i> : +33 9 83 86 84 15</a>
                            <a href="mailto:m.najafy@hotmail.com" class="btn btn-outline-brown"><i class="fas fa-envelope"></i> : Dr M. Barber Shop</a>
                        </div> <!-- end col-5 -->
                    </div> <!-- end row -->
                </div> <!-- end container -->
            </div> <!-- end section -->
            <p class="copyright">&copy 2020<a href="mailto:m.najafy@hotmail.com">m.najafy@hotmail.com</a></p>
        </footer> <!-- end footer -->
        <?= $this->body() ?>
    </body>
</html>