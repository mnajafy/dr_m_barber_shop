<?php
use App\Assets\AppAsset;
use Core\Url;
/* @var $this \Core\View */
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
                <a class="nav-brand" href="<?= Url::to(['home/index']) ?>"><img src="<?= Framework::getAlias('@web/assets/img/site/logoBrand.jpg') ?>" alt=""></a>
                <button class="dropdown-bars">Menu <span></span></button>
                <div class="navbar-collapse">
                    <ul>
                        <li>
                            <a class="nav-link" href="<?= Url::to(['hairdresser/index', '#' => 'contactez-nous']) ?>">
                                <i class="fas fa-mobile-alt"></i>
                                contactez-nous
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="<?= Url::to(['hairdresser/index', '#' => 'nos-horaire']) ?>">
                                <i class="fas fa-clock"></i> 
                                Infos horaires
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="<?= Url::to(['hairdresser/index']) ?>">
                                <i class="fas fa-store-alt"></i>
                                salon de coiffure
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="<?= Url::to(['gallery/index']) ?>">
                                <i class="fas fa-images"></i>
                                lyon en photos
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="banner banner-other">
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <ol>
                            <?php
                            if (isset($this->params['breadcrumb']) && is_array($this->params['breadcrumb'])) {
                                foreach ($this->params['breadcrumb'] as $breadcrumb) {
                                    if (is_array($breadcrumb)) {
                                        echo '<li class="breadcrumb-item"><a href="' . Url::to($breadcrumb['url']) . '">' . $breadcrumb['label'] . '</a></li>';
                                    }
                                    elseif (is_string($breadcrumb)) {
                                        echo '<li class="breadcrumb-item">' . $breadcrumb . '</li>';
                                    }
                                }
                            }
                            ?>
                        </ol>
                    </div>
                </div>
            </div>
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
                        </div>
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
                        </div>
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
                        </div>
                    </div>
                </div>
            </div>
            <p class="copyright">&copy 2020<a href="mailto:m.najafy@hotmail.com">m.najafy@hotmail.com</a></p>
        </footer>
        <?= $this->body() ?>
    </body>
</html>