<?php
$options = arcane_get_theme_options();
if(!isset($options['header_bg']['url']))$options['header_bg']['url'] = get_theme_file_uri('img/header.jpg');
if(!isset($options['top_grad_color']))$options['top_grad_color'] = '#ffc400';
if(!isset($options['bottom_grad_color']))$options['bottom_grad_color'] = '#ff8800';
if(!isset($options['primary_color']))$options['primary_color'] = '#ff8800';


$hex2 = arcane_hex2rgb($options['primary_color']);


$primary_color = $options['primary_color'];
$anchor_hover_color = '';
if(isset( $options['anchor_hover_color']))
$anchor_hover_color = $options['anchor_hover_color'];
$top_grad_color = '';
if(isset( $options['secondary_color']))
$top_grad_color = $options['secondary_color'];
$stripe= get_theme_file_uri('img/stripe.png');
$header_bg = $options['header_bg']['url'];
$pattern = get_theme_file_uri('img/pattern.png');

$header_bgtop = '';
if(isset($options['header-bg-gradient_top']['rgba']))
$header_bgtop = $options['header-bg-gradient_top']['rgba'];

$header_bgbottom = '';
if(isset($options['header-bg-gradient_bottom']['rgba']))
$header_bgbottom = $options['header-bg-gradient_bottom']['rgba'];

$header_tint_top = '';
if(isset($options['header-settings-tint-top']['rgba']))
	$header_tint_top = $options['header-settings-tint-top']['rgba'];

$header_tint_bottom = '';
if(isset($options['header-settings-tint-bottom']['rgba']))
	$header_tint_bottom = $options['header-settings-tint-bottom']['rgba'];



if(isset($cat_data["catBG"])) $catbg = $cat_data["catBG"];
if(!isset($catbg)) $catbg = '';



$headerbck_image = get_theme_file_uri('img/registerbg.jpg');
if(isset($options['header-settings-default-image']['url']))$headerbck_image = $options['header-settings-default-image']['url'];



$arcane_colors_css = "

header{

background-image: url($headerbck_image);
}

.title_wrapper:before {
background: linear-gradient(90deg, $header_tint_bottom 0%, $header_tint_top 100%) !important;
}

.navbar-wrapper{

background: linear-gradient(90deg, $header_bgbottom 0%, $header_bgtop 100%);
}


.accordion-group .active .regulation_text a, .editable-click, .latest-post-w li .info > a:hover, .match-header .mscore span,
a.editable-click, body .woocommerce .woocommerce-info .showcoupon,#invite #buddypress ul.item-list li div.action, .thinforight h2, 
.user-wrap a i, .bbp-forum-content ul.sticky .fa-comment, .bbp-topics ul.sticky .fa-comment, .bbp-topics ul.super-sticky .fa-comment, .bbp-topics-front ul.super-sticky .fa-comment, #buddypress .activity-list li.load-more a, body .vc_carousel.vc_per-view-more .vc_carousel-slideline .vc_carousel-slideline-inner > .vc_item > .vc_inner h2 a:hover,
#bbpress-forums fieldset.bbp-form legend, .newsbh li:hover a, .newsbv li:hover a, .cart-notification span.item-name, .woocommerce div.product p.price, .price span.amount,
.woocommerce .widget_shopping_cart .total span, .nm-date span, .cart-notification span.item-name, .woocommerce div.product p.price, .price span.amount,
.dropdown:hover .dropdown-menu li > a:hover, .team-generali .team-members-app > .fas, .thinfoleft h2, .navbar-inverse .nav > li > a:hover,
.nextmatch_wrap:hover .nm-teams span, input[type='password']:focus, input[type='datetime']:focus, input[type='datetime-local']:focus, input[type='date']:focus,
input[type='month']:focus, input[type='time']:focus, input[type='week']:focus, input[type='number']:focus, input[type='email']:focus,
input[type='url']:focus, input[type='search']:focus, input[type='tel']:focus, input[type='color']:focus, .uneditable-input:focus{
	color: $primary_color !important;
}

body.woocommerce div.product .woocommerce-tabs .panel h2, .btn-border, .tc-forms .register-form-wrapper ul.tbmaps li:hover, .tc-forms .register-form-wrapper ul.tbmaps li.selected,
li.theChampSelectedTab, .tbbrakets .tbbstagesw ul li.active a, body .popover-title, #searchform input:hover,.title-wrapper,
.tbbrakets .tbbstagesw ul li:hover a, .tbinfo ul li, .after-nav, .ticker-title:after,  ul.teams-list li a:hover img,
.item-options#members-list-options a.selected, .item-options#members-list-options a:hover,  ul.tc-games li.active, ul.tc-games li:hover, ul.tc-games-team li.active, ul.tc-games-team li:hover,
.item-options#members-list-options, .gallery-item a img, .match-map .map-image img, .nextmatch_wrap:hover img, 
.wrap:hover .team1img, .matchimages img, .widget .teamwar-list > li:first-child, .footer_widget .teamwar-list > li:first-child{
	border-color: $primary_color !important;
}

.is-style-outline>.wp-block-button__link:not(.has-text-color), .wp-block-button__link.is-style-outline:not(.has-text-color), body a.tbd_score, body nav.ubermenu-main .ubermenu-submenu .ubermenu-current-menu-item > .ubermenu-target, .tournaments-list h4, #buddypress div.item-list-tabs ul li a span, .widget.teamwarlist-page .teamwar-list .date strong, .footer_widget.teamwarlist-page .teamwar-list .date strong, #matches .mminfo span, .footer_widget .teamwar-list .home-team, .footer_widget .teamwar-list .vs, .footer_widget .teamwar-list .opponent-team, .widget .teamwar-list .home-team, .widget .teamwar-list .vs, .widget .teamwar-list .opponent-team, div.bbp-template-notice, div.indicator-hint, .social a , .widget-title i, .profile-teams a:hover, .friendswrapper .friends-count i, .slider_com_wrap, .portfolio .row .span8 .plove a:hover, .span3 .plove a:hover, .icons-block i:hover,
.navbar-inverse .nav > li > a:hover > span, .navbar-inverse .navbar-nav > li.active > a > span,.tc-forms > span, .navbar-inverse .navbar-nav > li.current-menu-item > a > span,  ul.news-main-list li a .nm-main span i,
 .similar-projects ul li h3,ul.matchstats li span, .ubermenu .ubermenu-icon, ul.teams-list li a:hover strong,  .widget .review li .info .overall-score, .tournament-creation-wrap.col-9 h3, .team-creation-wrap.col-9 h3,
 .member h3, .main-colour, body a, .dropdown-menu li > a:hover, .wallnav i,  div.rating:after, footer .copyright .social a:hover, .navbar-inverse .brand:hover, .member:hover > .member-social a, footer ul li a:hover, .widget ul li a:hover, .next_slide_text .fa-bolt ,
  .dropdown-menu li > a:focus, .dropdown-submenu:hover > a,ul.tournaments-list li .tlinfow .tlist-info small, ul.news-main-list li:hover a strong, ul.news-main-list li:first-child:hover a strong, .extra-blurb .social-top a:hover, ul.matches-wrapper li:hover a > div.mw-left div strong, ul.matches-wrapper li:hover a > div.mw-right div strong,
  .comment-body .comment-author, .normal-page a,  .cart-wrap a, .bx-next-out:hover .next-arrow:before, body .navbar-wrapper .login-info .login-btn, body.page .tournaments_block_single .wcontainer li h4 {
    color: $primary_color;
}
body .newsbv li .ncategory, .ncategory, .user-wrap > a, .after-nav .container:before, .ticker-title, #sform input[type=search], .item-options#members-list-options a.selected, .item-options#members-list-options a:hover, div.pagination a:focus, div.pagination a:hover, div.pagination span.current, .page-numbers:focus, .page-numbers:hover, .page-numbers.current, body.woocommerce nav.woocommerce-pagination ul li a:focus, body.woocommerce nav.woocommerce-pagination ul li a:hover, body.woocommerce nav.woocommerce-pagination ul li span.current, .widget .teamwar-list .tabs li:hover a, .widget .teamwar-list .tabs li.selected a, .bgpattern, .woocommerce .cart-notification, .cart-notification, .splitter li[class*='selected'] > a, .splitter li a:hover, .ls-wp-container .ls-nav-prev, .ls-wp-container .ls-nav-next, a.ui-accordion-header-active, .accordion-heading:hover, .block_accordion_wrapper .ui-state-hover, .cart-wrap, .teamwar-list li ul.tabs li:hover, .teamwar-list li ul.tabs li.selected a:hover, .teamwar-list li ul.tabs li.selected a, .dropdown .caret,
.tagcloud a, .progress-striped .bar ,  .bgpattern:hover > .icon, .progress-striped .bar, .member:hover > .bline, .blog-date span.date, .matches-tabw .matches-tab li a:hover, .matches-tabw .matches-tab li.active a, 
 .pbg, .pbg:hover, .pimage:hover > .pbg, ul.social-media li a:hover, .navigation a ,#buddypress div.item-list-tabs ul li.current, #buddypress div.item-list-tabs ul li:hover,
 button[type='submit']:hover, button[type='submit']:focus, button[type='submit']:active, .elementor-widget-sw_matches .nav-tabs li a:hover,body #buddypress .nav-tabs > li:hover,
button[type='button']:hover, button[type='button']:focus, button[type='button']:active,.elementor-widget-sw_matches .nav-tabs li.active, #buddypress .nav-tabs > li.ui-tabs-active,
button:hover, button:focus, button:active, body .gamesb li img, .btn, .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus,
 .pagination ul > .active > a, .pagination ul > .active > span, .list_carousel a.prev:hover, .list_carousel a.next:hover, .pricetable .pricetable-col.featured .pt-price, .block_toggle .open, .pricetable .pricetable-featured .pt-price, .isotopeMenu, .bbp-topic-title h3, .modal-body .reg-btn, #LoginWithAjax_SubmitButton .reg-btn, buddypress div.item-list-tabs ul li.selected a, .results-main-bg, .blog-date-noimg, .blog-date, .ticker-wrapper.has-js, .ticker-swipe, .pagination ul.wp_pag > li,
 .sk-folding-cube .sk-cube:before, body .bp-navs ul li .count, ul.matches-wrapper li a > div.mw-mid span, .team-page .team-nav > a, .search-top i , header .dropdown .dropdown-menu li a:before,
.rotating-plane, .double-bounce1, .double-bounce2, .rectangle-bounce > div, .elementor-widget-sw_matches .nav-tabs li.active, .upcoming,.spcard.sticky,  p.wp-block-tag-cloud a,
.cube1, .cube2, .pulse, .dot1, .dot2, .three-bounce > div, .sk-circle .sk-child:before, .btn-border:hover, ul.news-main-list li a > span, ul.news-small-list li a:before,
.sk-cube-grid .sk-cube, .sk-fading-circle .sk-circle:before,  .widget .teamwar-list > ul li.active a, #buddypress .item-list-tabs ul li a span,.wp-block-button__link,
 .buddypress .buddypress-wrap .activity-read-more a, .buddypress .buddypress-wrap .comment-reply-link, .buddypress .buddypress-wrap .generic-button a, .buddypress .buddypress-wrap a.bp-title-button, .buddypress .buddypress-wrap a.button, .buddypress .buddypress-wrap button, .buddypress .buddypress-wrap input[type=button], .buddypress .buddypress-wrap input[type=reset], .buddypress .buddypress-wrap input[type=submit], .buddypress .buddypress-wrap ul.button-nav:not(.button-tabs) li a{
    background-color: $primary_color;
}
.tbprice::-webkit-scrollbar-thumb{
background-color: $primary_color;
}

body .btn-primary, body .btn-default, body .progress-bar, body #sform input[type=search], body #sformm input[type=search], .vc_tta-tab, #matches .match-fimage .mversus .deletematch, .navbar-wrapper .login-info .login-btn .fas,
.teamwar-list .upcoming, #matches ul.cmatchesw li .deletematch, body .vc_tta-color-grey.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title, .friendswrapper .add-friend, .msg_ntf,
.footer_widget .teamwar-list .scores, .widget .teamwar-list .scores, .user-avatar, .woocommerce-page .product-wrap a.button, .button-medium, a.group-button, .button-small, .button-big, button[type='submit'], input[type='submit'], input[type='button'],
.footer_widget .teamwar-list .upcoming, .widget .teamwar-list .upcoming, .user-wrap a.btns, .cart-outer,
.footer_widget .teamwar-list .playing, .widget .teamwar-list .playing, .pricetable .pricetable-col.featured .pt-top, .pricetable .pricetable-featured .pt-top,
.after-nav .login-tag, .next-line, .bx-wrapper .bx-pager.bx-default-pager a:hover:before,
.bx-wrapper .bx-pager.bx-default-pager a.active:before, .after-nav .login-info a, .team-page .team-nav li, .wpb_tabs_nav li, .nav-tabs > li,  #TB_ajaxContent button,
 button[type='submit']:hover, button[type='submit']:focus, button[type='submit']:active,
button[type='button']:hover, button[type='button']:focus, button[type='button']:active,
button:hover, button:focus, button:active, .footer_widget .teamwar-list .upcoming,
.widget .teamwar-list .upcoming, .g_winner,
 .blog-date span.date, .blog-date-noimg span.date, .teamwar-list .draw, .carousel-indicators .active, .after-nav .login-info input[type='submi'], .after-nav .login-info a:hover{
	background-color: $primary_color;
}
p.wp-block-tag-cloud a:hover, input[type='submit']:hover, a.added_to_cart:hover, .wp-block-button__link:hover, .friendswrapper .add-friend:hover,.btn:hover, .button:hover, body .btn-primary, .btn-primary:active:hover, .btn-primary.active:hover, .open > .dropdown-toggle.btn-primary:hover, .btn-primary:active:focus, .btn-primary.active:focus, .open > .dropdown-toggle.btn-primary:focus, .btn-primary:active.focus, .btn-primary.active.focus, .open > .dropdown-toggle.btn-primary.focus{
	background-color: $top_grad_color !important;
}
input[type='submit']:hover, a.added_to_cart:hover, .btn:hover, .button:hover{
    background-color:$top_grad_color;
}  
a:hover{
   color:$anchor_hover_color !important;
}

.boxshadoweffect:hover{
	box-shadow: 0px 2px 20px 0px $top_grad_color;
}
.slider_com_wrap *, ul.about-profile li, .topgradcolor, .post-pinfo a, .post-pinfo, .tlistinfo i, .post-review .score, .overall-score{
	color:$top_grad_color !important;
}
 

.sk-folding-cube .sk-cube:before , #buddypress #user-notifications .count, .tbbrakets .tbbstagesw ul li:hover a, #buddypress div.item-list-tabs ul li a span,
.buddypress-wrap .bp-navs li:not(.current) a:focus, .buddypress-wrap .bp-navs li:not(.current) a:hover, .buddypress-wrap .bp-navs li:not(.selected) a:focus, .buddypress-wrap .bp-navs li:not(.selected) a:hover,
.buddypress-wrap .bp-navs li.current a, .buddypress-wrap .bp-navs li.current a:focus, .buddypress-wrap .bp-navs li.current a:hover, .buddypress-wrap .bp-navs li.selected a, .buddypress-wrap .bp-navs li.selected a:focus, .buddypress-wrap .bp-navs li.selected a:hover,
body .btn-default, .button-medium, .button-small, .button-big, button[type='submit'], input[type='submit']{
	background-color:$primary_color!important;
}

.ticker-title:before {
    border-bottom: 38px solid $primary_color;
}
.next-arrow{
	border-left: 30px solid $top_grad_color;
}

.tbmapsi li:hover > div , .tbmapsi li.selected_map > div,div.bbp-template-notice, div.indicator-hint,  div.pagination a, div.pagination span,body.woocommerce nav.woocommerce-pagination ul li a, body.woocommerce nav.woocommerce-pagination ul li span, .pagination ul.wp_pag > li {
	border: 1px solid $primary_color;
}
.g_current, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button, .woocommerce div.product .woocommerce-tabs ul.tabs li a, .woocommerce #content div.product .woocommerce-tabs ul.tabs li a, .woocommerce-page div.product .woocommerce-tabs ul.tabs li a, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li a {
	background: $primary_color  !important;
}
 
.teamwar-list > ul li.ui-tabs-active a, .teamwar-list > ul li a:hover,  .mcbackground, .woocommerce span.onsale, .woocommerce-page span.onsale,
 .elementor-widget-sw_matches .nav-tabs li a:hover, .elementor-widget-sw_matches .nav-tabs li.ui-tabs-active a,
 .woocommerce .widget_price_filter .ui-slider .ui-slider-range, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-range{
	background:$primary_color !important;
}
 
textarea:focus,
input[type='text']:focus,
input[type='password']:focus,
input[type='datetime']:focus,
input[type='datetime-local']:focus,
input[type='date']:focus,
input[type='month']:focus,
input[type='time']:focus,
input[type='week']:focus,
input[type='number']:focus,
input[type='email']:focus,
input[type='url']:focus,
input[type='search']:focus,
input[type='tel']:focus,
input[type='color']:focus,
.uneditable-input:focus,
textarea:focus,
input[type='text']:hover,
input[type='password']:hover,
input[type='datetime']:hover,
input[type='datetime-local']:hover,
input[type='date']:hover,
input[type='month']:hover,
input[type='time']:hover,
input[type='week']:hover,
input[type='number']:hover,
input[type='email']:hover,
input[type='url']:hover,
input[type='search']:hover,
input[type='tel']:hover,.widget h4,
input[type='color']:hover, footer .widget h3.widget-title,
.uneditable-input:hover,select:hover, select:active,
.gallery-item a img:hover{
	border-color: $primary_color !important;
}

/* Backgrounds */

body{
	background:url( $header_bg) no-repeat center top;
}

/* Background Tint */

.owl-item .car_image:after{
	background: url($pattern) top left repeat $catbg;
}
.team-creation-wrap.col-9 .reg-team-platform input[type='checkbox'] {
 --border-hover:$primary_color;
     --active: $primary_color;
 }
 

";

?>