<?php
	$company_phone = do_shortcode( '[lpw_company_phone]' );
?>

<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" >

	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

<header id="site-header" class="header-footer-group" role="banner">
	<div class="header__wrap">
		<div class="header-logo">
			<div class="header-logo__img">
				<?php echo do_shortcode( '[lpw_company_logo size="small"]' ); ?>
				<?php echo do_shortcode( '[lpw_company_mobile_logo size="small" class="header-logo__img-mobile"]' ); ?>
			</div>
			<div class="header-logo__content">
				<h1><?php ?><?php echo do_shortcode( '[lpw_company_name]' ); ?></h1>
			</div>
		</div>
		<div class="header-phone">
			<div class="header-phone__img">
				<i class="fa fa-phone fa-5x" aria-hidden="true" style="color:red"></i>
			</div>
            <div class="header-phone-info">
            	<a class="header-phone-info__number" href="tel:<?php echo $company_phone; ?>"><?php echo $company_phone; ?></a>
                <p>4.9 Overall Rating
                    <br><i class="fa fa-star fa" aria-hidden="true" style="color:gold"></i><i class="fa fa-star fa" aria-hidden="true" style="color:gold"></i><i class="fa fa-star fa" aria-hidden="true" style="color:gold"></i><i class="fa fa-star fa" aria-hidden="true" style="color:gold"></i><i class="fa fa-star fa" aria-hidden="true" style="color:gold"></i>
                </p>
            </div>
		</div>
        <div class="burger-wrap">
            <div class="burger"><i class="fa fa-bars"></i>
            </div>
        </div>
	</div>
    <div class="section-navigation">
        <div class="container">
            <ul class="navigation" id="menu-header">
                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-68" id="menu-item-68"><a href="#">About us</a>
                </li>
                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-68" id="menu-item-68"><a href="#">Services</a>
                </li>
                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-68" id="menu-item-68"><a href="#">Sample Page</a>
                </li>
                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-68" id="menu-item-68"><a href="#">Career</a>
                </li>
                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-68" id="menu-item-68"><a href="#">Reviews</a>
                </li>
            </ul>
        </div>
    </div>
</header>

<!-- Content --->
<main class="content">
	<section class="section-welcome"style='background-image: url("<?php echo do_shortcode( '[lpw_slider_background type="url"]' ); ?>");'>
        <div class="container">
        	<h2 class="welcome__title"><?php echo do_shortcode( '[lpw_slider_headline]' ); ?><br><span class="welcome__inscription"><?php echo do_shortcode( '[lpw_slider_headline]' ); ?></span></h2>
        	<ul class="welcome__list">
                <?php
                    for( $i = 1; $i <= 5; $i++ ){
                        $sbp = do_shortcode( "[lpw_slider_bullet_point bullet_point={$i}]" );
                        if( empty( $sbp ) ) continue;
                ?>
                        <li><?php echo $sbp; ?></li>
                <?php
                    }
                ?>
        	</ul>
            <div class="welcome-speak">
                <div class="welcome-speak-block">
                	<a href="tel:<?php echo $company_phone; ?>"><i class="far fa-comments" aria-hidden="true"></i>SPEAK TO AN EXPERT NOW</a>
                </div>
                <div class="welcome-speak-rating welcome-speak-block">
                    <p>CHECK OUT OUR REVIEWS</p>
                    <div class="welcome-speak-rating__line"></div>
                    <p>4.9 Overall Rating</p>
                    <div class="welcome-speak-rating__img"></div><i class="fa fa-star fa-2x" aria-hidden="true" style="color:gold"></i><i class="fa fa-star fa-2x" aria-hidden="true" style="color:gold"></i><i class="fa fa-star fa-2x" aria-hidden="true" style="color:gold"></i><i class="fa fa-star fa-2x" aria-hidden="true" style="color:gold"></i><i class="fa fa-star fa-2x" aria-hidden="true" style="color:gold"></i>
                </div>
            </div>
            <div class="lpw-line-img">
                <?php
                    for( $i = 1; $i <= 5; $i++ ){
                        $ts = do_shortcode( "[lpw_trusted_symbol trusted_symbol={$i}]" );
                        if( empty( $ts ) ) continue;
                        echo $ts;
                    }
                ?>
            </div>
        </div>
	</section>
	<section class="section-about-experts">
        <div class="about-experts__title global-title">
            <div class="container">
                <h1><?php echo do_shortcode( '[lpw_block_headline infoblock=1]' ); ?></h1>
            </div>
        </div>
        <div class="container">
        	<?php echo do_shortcode( '[lpw_block_text infoblock=1]' ); ?>
        </div>
	</section>
    <section class="section-insurance-carrier">
        <div class="container">
            <div class="insurance-carrier__title global-title">
                <h2><?php echo do_shortcode( '[lpw_block_headline infoblock=2]' ); ?></h2>
            </div>
            <section class="elementor-element elementor-element-0633a4d elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-inner-section" data-id="0633a4d" data-element_type="section">
            	<?php echo do_shortcode( '[lpw_block_text infoblock=2]' ); ?>
            </section>
        </div>
    </section>
    <section class="section-last-projects">
        <div class="container">
            <div class="last-projects__title">
                <h2><?php echo do_shortcode( '[lpw_last_project_headline]' ); ?></h2>
            </div>
            <!-- Ant -->
            <div class="ant-carousel" id="lpw-projects">
                <div class="ant-carousel-hider">
                    <div class="ant-carousel-list">
                        <?php
                        for( $i = 1; $i <= 5; $i++ ){
                            $lpp = do_shortcode( "[lpw_last_project_picture last_project={$i}]" );
                            if( empty( $lpp ) ) continue;
                        ?>
                            <div class="ant-carousel-element ant-carousel-element-img">
                                <?php echo $lpp; ?>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="ant-carousel-arrow-left"></div>
                <div class="ant-carousel-arrow-right"></div>
                <div class="ant-carousel-dots"></div>
            </div>
            <!-- End Ant -->
        </div>
    </section>
    <section class="section-wait-result">
        <div class="container">
            <div class="wait-result__title">
                <h2>What to Expect</h2>
            </div>
            <div class="wait-result__wrap">
                <div class="wait-result__img">
                    <?php echo do_shortcode( '[lpw_w2e_photo]' ); ?><a class="global-btn" href="tel:<?php echo $company_phone; ?>">SPEAK TO AN EXPERT NOW</a>
                </div>
                <ul class="wait-result-list">
                    <?php
                        for( $i = 1; $i <= 5; $i++ ){
                            $bp = do_shortcode( "[lpw_w2e_bullet_point bullet_point={$i}]" );
                            if( empty( $bp ) ) continue;
                    ?>
                        <li class="wait-result-list__item"><i class="fa fa-check-circle fa-3x" aria-hidden="true"></i>
                            <p><?php echo $bp ?></p>
                        </li>
                    <?php
                        }
                    ?>
                </ul>
            </div><a class="global-btn wait-result__btn-mobile" href="tel:(888) 502-7190">SPEAK TO AN EXPERT NOW</a>
        </div>
    </section>
    <section class="section-customer-testimonials">
        <div class="container">
            <div class="customer-testimonials__title">
                <h2>Customer Testimonials</h2>
            </div>
            <!-- Ant -->
            <div class="ant-carousel" id="lpw-testimonials">
                <div class="ant-carousel-hider">
                    <div class="ant-carousel-list">
                        <?php
                        for( $i = 1; $i <= 10; $i++ ){
                            $testimonial = do_shortcode( "[lpw_testimonial testimonial={$i}]" );
                            if( empty( $testimonial ) ) continue;
                        ?>
                            <div class="ant-carousel-element ant-carousel-element-text">
                                <div class="customer-testimonials-slider-item">
                                    <div class="customer-testimonials-slider-item__wrap"><i class="fa fa-star fa-2x" aria-hidden="true" style="color:gold"></i><i class="fa fa-star fa-2x" aria-hidden="true" style="color:gold"></i><i class="fa fa-star fa-2x" aria-hidden="true" style="color:gold"></i><i class="fa fa-star fa-2x" aria-hidden="true" style="color:gold"></i><i class="fa fa-star fa-2x" aria-hidden="true" style="color:gold"></i>
                                        <p><?php echo $testimonial;?></p>
                                    </div>
                                </div>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="ant-carousel-arrow-left"></div>
                <div class="ant-carousel-arrow-right"></div>
                <div class="ant-carousel-dots"></div>
            </div>
            <!-- End Ant -->
            <div class="customer-testimonials__more-reviews"><a href="#">MORE REVIEWS</a>
            </div>
        </div>
    </section>
</main>

<!-- .wrapper-->
<footer class="footer">
    <div class="container">
        <div class="footer__wrap">
            <div class="footer-block footer-block-left">
                <div class="footer-block-left__list">
                    <p><?php echo do_shortcode( "[lpw_company_name]" );?></p>
                    <p><?php echo $company_phone;?></p>
                    <p><?php echo do_shortcode( "[lpw_company_address]" );?></p>
                    <p>Local branch for immediate
                        <br>service 24/7 365 Days a Year.:</p>
                    <p><?php
                    	$sccp = do_shortcode( "[lpw_state]" ).' '.do_shortcode( "[lpw_county]" ).' '.do_shortcode( "[lpw_city]" ).'  '.do_shortcode( "[lpw_city_phone]" );
                    	echo $sccp;
                    ?></p>
                    <p>There for you when it counts the most &reg;</p>
                </div><a class="footer-block-left__phone" href="<?php echo $company_phone; ?>"><?php echo $company_phone; ?></a>
            </div>
            <div class="footer-block footer-block-center">
                <div class="footer-block-center__img">
                    <?php echo do_shortcode( '[lpw_company_logo]' ); ?>
                </div>
                <div class="footer-block-center-social"><a href="<?php echo do_shortcode( '[lpw_company_social social="facebook"]' ); ?>" target="_blank"><i class="fab fa-facebook" aria-hidden="true"></i></a><a href="<?php echo do_shortcode( '[lpw_company_social social="twitter"]' ); ?>" target="_blank"><i class="fab fa-twitter" aria-hidden="true"></i></a><a href="<?php echo do_shortcode( '[lpw_company_social social="instagram"]' ); ?>" target="_blank"><i class="fab fa-pinterest" aria-hidden="true"></i></a><a href="<?php echo do_shortcode( '[lpw_company_social social="linkedin"]' ); ?>" target="_blank"><i class="fab fa-linkedin" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="footer-block footer-block-right">
                <p class="footer-block-right__title">SERVICE</p>
                <ul class="footer-block-right__list" id="menu-header-1">
                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-68"><i class="fa fa-play" aria-hidden="true"></i><a href="#">Sample Page</a>
                    </li>
                </ul>
                <div class="footer-block-right-rating">
                    <p class="footer-block-right-rating__text">Top-Rated Local Company</p>
                    <div class="footer-block-right-rating__stars" title="5/5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="menu-footer-container">
            <?php
                $is_html_sitemap = BLPW_Options::get_option( 'blpw_company_info_block', 'html_sitemap', 'off', 'wp' );
                if( $is_html_sitemap != 'off' && $is_html_sitemap != false ){
                    $url_sitemap = get_site_url().'/index.php/sitemap/';
                    $a_sitemap = "<a href='{$url_sitemap}'>Sitemap</a>";
                    echo '<div class="footer-navigation">';
                    echo $a_sitemap;
                    echo '</div>';

                }
            ?>
        </div>
        <div class="footer-copyright">
            <ul>
                <li><?php echo do_shortcode( '[lpw_company_name]' ); ?> | All Rights Reserved</li>
            </ul>
        </div>
    </div>
</footer>

    <script type="text/javascript">
        new Ant( 'lpw-projects', {
            elemVisible: 3
            ,auto: false
            ,loop: false
        } );
        new Ant( 'lpw-testimonials', {
            elemVisible: 1
            ,auto: false
            ,loop: true
        } );
    </script>

</body>

</html>