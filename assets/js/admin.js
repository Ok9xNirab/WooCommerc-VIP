jQuery(document).ready(($) => {
	const vip_role = $('#nirab_wv_vip_role');
	const vip_price_section = $('.nirab_wv_vip_price_field');

	checkIfEnabled();
	vip_role.on('change', checkIfEnabled);

	function checkIfEnabled() {
		if ('none' === vip_role.val()) {
			vip_price_section.hide();
		} else {
			vip_price_section.show();
		}
	}
});
