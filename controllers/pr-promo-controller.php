<?php

/**
 * Class for editing member account
 */

class PR_Promos {

	public $natgeo_entries;

	function __construct() {

		add_shortcode('pr_natgeo_promo', array($this, 'show_natgeo_entries'));

	}

	function show_natgeo_entries() {

		require_once( WPPR_PLUGIN_DIR . '/models/promo-model.php' );
		$model = new Promo_Model;
		$model->start_date = '2016-03-02';
		$model->end_date = '2016-04-09';

		$attributes['entries'] = $model->get_complete_profiles();
		$attributes['inc_entries'] = $model->get_incomplete_profiles();
		$total = $model->get_total_entries();
		$valid = $model->get_valid_entries();

		$attributes['total'] = $total->total_entries;
		$attributes['valid'] = $valid->valid_entries;
		 
		return PR_Membership::get_html_template( 'natgeo', $attributes );
	}


}
