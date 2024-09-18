<tr class="form-field">
	<th><label for="nirab_vip_role"><?php esc_html_e( 'VIP Role', 'nirab_wv' ); ?></label></th>
	<td>
		<select name="nirab_vip_role" id="nirab_vip_role">
		<option value="none">
			<?php esc_html_e( 'None', 'nirab_wv' ); ?>
		</option>
		<?php wp_dropdown_roles( $selected_role ); ?>
	</select>
	<p><?php esc_html_e( 'Set your role to restrict access the Category.', 'nirab_wv' ); ?></p>
	</td>
</tr>
