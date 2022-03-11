<?php

if(class_exists('\Elementor\Base_Data_Control')) {
    class Control_Multiselect extends \Elementor\Base_Data_Control
    {

        public function get_type()
        {
            return 'multiselect';
        }


        public function content_template()
        {
            $control_uid = $this->get_control_uid();
            ?>
            <div class="elementor-control-field">
                <label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label
                    }}}</label>
                <div class="elementor-control-input-wrapper">
                    <select id="<?php echo esc_attr($control_uid); ?>" data-setting="{{ data.name }}" multiple
                            style="min-height: 100px; padding: 5px; overflow-y: auto;">
                        <# _.each( data.options, function( option_title, option_value ) { #>
                        <option value="{{ option_value }}">{{{ option_title }}}</option>
                        <# } ); #>
                    </select>
                </div>
            </div>
            <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
            <# } #>
            <?php
        }
    }
}
