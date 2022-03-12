<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$owl_settings = array(
    'items' => array(
        'name' => esc_html__('Items','arcane'),
        'desc' => esc_html__('The number of items you want to see on the screen.','arcane'),
        'default' => 3,
        'cmb_type' => 'text',
        'type' => 'number'
    ),
    'itemsDesktop' => array(
        'name' => esc_html__('Items Desktop','arcane'),
        'desc' => esc_html__('The number of items on desktop resolutions (1199px)','arcane'),
        'default' => 4,
        'cmb_type' => 'text',
        'type' => 'number'
    ),
    'itemsDesktopSmall' => array(
        'name' => esc_html__('Items Desktop Small','arcane'),
        'desc' => esc_html__('The number of items on small desktop resolutions (979px)','arcane'),
        'default' => 3,
        'cmb_type' => 'text',
        'type' => 'number'
    ),
    'itemsTablet' => array(
        'name' => esc_html__('Items Tablet','arcane'),
        'desc' => esc_html__('The number of items on tablet resolutions (768px)','arcane'),
        'default' => 2,
        'cmb_type' => 'text',
        'type' => 'number'
    ),
    'itemsMobile' => array(
        'name' => esc_html__('Items Mobile','arcane'),
        'desc' => esc_html__('The number of items on mobile resolutions (479px)','arcane'),
        'default' => 1,
        'cmb_type' => 'text',
        'type' => 'number'
    ),
    'slideSpeed' => array(
        'name' => esc_html__('Slide Speed','arcane'),
        'desc' => esc_html__('Slide speed in milliseconds.','arcane'),
        'default' => 200,
        'cmb_type' => 'text',
        'type' => 'number'
    ),
    'paginationSpeed' => array(
        'name' => esc_html__('Pagination Speed','arcane'),
        'desc' => esc_html__('Pagination speed in milliseconds.','arcane'),
        'default' => 800,
        'cmb_type' => 'text',
        'type' => 'number'
    ),
    'rewindSpeed' => array(
        'name' => esc_html__('Rewind Speed','arcane'),
        'desc' => esc_html__('Rewind speed in milliseconds.','arcane'),
        'default' => 1000,
        'cmb_type' => 'text',
        'type' => 'number'
    ),
    'rewindNav' => array(
        'name' => esc_html__('Rewind Nav','arcane'),
        'desc' => esc_html__('Slide to first item.','arcane'),
        'default' => true,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'singleItem' => array(
        'name' => esc_html__('Single Item','arcane'),
        'desc' => esc_html__('Display only one item.','arcane'),
        'default' => false,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'autoPlay' => array(
        'name' => esc_html__('Auto Play','arcane'),
        'desc' => esc_html__('Change to any integrer for example autoPlay : 5000 to play every 5 seconds, or 0 to disable autoPlay.','arcane'),
        'default' => 0,
        'cmb_type' => 'text',
        'type' => 'number'
    ),
    'stopOnHover' => array(
        'name' => esc_html__('Stop On Hover','arcane'),
        'desc' => esc_html__('Stop autoplay on mouse hover','arcane'),
        'default' => false,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'navigation' => array(
        'name' => esc_html__('Navigation','arcane'),
        'desc' => esc_html__('Display "next" and "prev" buttons.','arcane'),
        'default' => false,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'navigationTextNext' => array(
        'name' => esc_html__('Navigation "Next"','arcane'),
        'desc' => esc_html__('Text on "Next" button','arcane'),
        'default' => 'Next ',
        'cmb_type' => 'text',
        'type' => 'string'
    ),
    'navigationTextPrev' => array(
        'name' => esc_html__('Navigation "Prev"','arcane'),
        'desc' => esc_html__('Text on "Prev" button','arcane'),
        'default' => 'Prev ',
        'cmb_type' => 'text',
        'type' => 'string'
    ),
    'pagination' => array(
        'name' => esc_html__('Pagination','arcane'),
        'desc' => esc_html__('Show pagination.','arcane'),
        'default' => true,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'paginationNumbers' => array(
        'name' => esc_html__('Pagination Numbers','arcane'),
        'desc' => esc_html__('Show numbers inside pagination buttons','arcane'),
        'default' => false,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'itemsScaleUp' => array(
        'name' => esc_html__('Item Scale Up','arcane'),
        'desc' => esc_html__('Option to not stretch items when it is less than the supplied items.','arcane'),
        'default' => false,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'scrollPerPage' => array(
        'name' => esc_html__('Scroll per Page','arcane'),
        'desc' => esc_html__('Scroll per page not per item. This affect next/prev buttons and mouse/touch dragging.','arcane'),
        'default' => false,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'responsive' => array(
        'name' => esc_html__('Responsive','arcane'),
        'desc' => esc_html__('You can use Owl Carousel on desktop-only websites too! Just change that to "false" to disable resposive capabilities','arcane'),
        'default' => true,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'responsiveRefreshRate' => array(
        'name' => esc_html__('Responsive Refresh Rate','arcane'),
        'desc' => esc_html__('Check window width changes every X ms for responsive actions','arcane'),
        'default' => 200,
        'cmb_type' => 'text',
        'type' => 'number'
    ),
    'lazyLoad' => array(
        'name' => esc_html__('Lazy Load','arcane'),
        'desc' => esc_html__('Delays loading of images. Images outside of viewport won\'t be loaded before user scrolls to them. Great for mobile devices to speed up page loadings. ','arcane'),
        'default' => false,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'lazyFollow' => array(
        'name' => esc_html__('Lazy Follow','arcane'),
        'desc' => esc_html__('When pagination used, it skips loading the images from pages that got skipped. It only loads the images that get displayed in viewport. If set to false, all images get loaded when pagination used. It is a sub setting of the lazy load function.','arcane'),
        'default' => false,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'autoHeight' => array(
        'name' => esc_html__('Auto Height','arcane'),
        'desc' => esc_html__('Add height to owl-wrapper-outer so you can use diffrent heights on slides. Use it only for one item per page setting.','arcane'),
        'default' => false,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'dragBeforeAnimFinish' => array(
        'name' => esc_html__('Drag Before Animation Finishes','arcane'),
        'desc' => esc_html__('Ignore whether a transition is done or not (only dragging).','arcane'),
        'default' => true,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'mouseDrag' => array(
        'name' => esc_html__('Mouse Drag','arcane'),
        'desc' => esc_html__('Turn off/on mouse events.','arcane'),
        'default' => true,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'touchDrag' => array(
        'name' => esc_html__('Touch Drag','arcane'),
        'desc' => esc_html__('Turn off/on touch events.','arcane'),
        'default' => true,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
    'addClassActive' => array(
        'name' => esc_html__('Add Class Active','arcane'),
        'desc' => esc_html__('Add "active" classes on visible items. Works with any numbers of items on screen.','arcane'),
        'default' => false,
        'cmb_type' => 'checkbox',
        'type' => 'bool'
    ),
);