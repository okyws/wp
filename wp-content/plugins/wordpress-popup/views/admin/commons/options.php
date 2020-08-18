<?php
/**
 * Templates for different field types and elements.
 * Markup for us, frontend dummies.
 * The variables that are used in this file are:
 *
 * $type          string   The field/element type.
 *                         select|checkboxes|checkbox|{anything else as a regular input}
 * $is_template   bool     Whether the field is used in an underscore template.
 * $id            string   ID property of the field.
 * $class         string   Classes of the field.
 * $name          string   Name of the field. For $is_template, it should
 *                         have the same name as the js property that contains its value.
 * $attributes    array    Associative array with other properties for the field. @see Hustle_Layout_Helper::render_attributes().
 *
 *
 * $options       array    Associative array where the key of the pair is the option's "value" property,
 *                         and the value of the pair is the displayed label of the option. Used by select|checkboxes.
 * $selected      string   Used only if ! $is_template. The current stored value of the field. Must match the
 *                |array  'key' of its respective option pair in the $options array. Used by select|checkboxes|checkbox.
 *
 *
 * $value         string   Value of the field.
 *                         Make sure it's properly escaped when rendering 'inline_notice'.
 * $placeholder   string   TO BE DEPRECATED favoring accessibility. Placeholder of the field.
 * $icon          string   Name of the icon as per SUI names. Used by text|number.
 *
 * NOTE: enable phpcs when editing stuff. Make sure what's left is okay. Disable it again afterwards.
 *
 * @package Hustle
 * @since 4.2.0
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

// Flag for when the option is used in underscore template files.
$is_template = isset( $is_template ) ? $is_template : false;
$attributes  = isset( $attributes ) ? $attributes : array();

switch ( $type ) :

	case 'select':
		?>
		<select
			id="<?php echo empty( $id ) ? 'hustle-select-' . esc_attr( $name ) : esc_attr( $id ); ?>"
			name="<?php echo esc_attr( $name ); ?>"
			<?php echo empty( $class ) ? '' : 'class="' . esc_attr( $class ) . '"'; ?>
			<?php $this->render_attributes( $attributes ); ?>
			tabindex="-1"
			aria-hidden="true"
		>

			<?php
			// Fully server's side rendered field.
			if ( ! $is_template ) :

				foreach ( $options as $value => $label ) :
					$label     = ! empty( $label ) ? $label : '&#8205;';
					$_selected = is_array( $selected ) && empty( $selected ) ? '' : $selected;
					?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $_selected, $value ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
					<?php
				endforeach;

			else : // Field expecting parameters from underscore templating.

				foreach ( $options as $value => $label ) :
					?>
					<option value="<?php echo esc_attr( $value ); ?>" {{ _.selected( ( "<?php echo $value; ?>" === <?php echo $name; ?> ), true ) }}>
						<?php echo esc_html( $label ); ?>
					</option>
					<?php
				endforeach;

			endif;
			?>

		</select>

		<?php
		break;

	case 'checkboxes':
		// Fully server's side rendered field.
		if ( ! $is_template ) :

			$_selected = isset( $selected ) ? $selected : array();
			if ( ! is_array( $_selected ) ) {
				$_selected = array( $_selected );
			}

			foreach ( $options as $value => $label ) :
				?>

				<label class="sui-checkbox <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>">

					<input
						type="checkbox"
						name="<?php echo esc_attr( $name ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						<?php echo isset( $id ) ? 'id="' . esc_attr( $id . '-' . $value ) . '"' : ''; ?>
						<?php $this->render_attributes( $attributes ); ?>
						<?php checked( in_array( $value, $_selected, true ) ); ?>
					/>

					<span aria-hidden="true"></span>

					<span><?php echo esc_html( $label ); ?></span>

				</label>

				<?php
			endforeach;

		else : // Field expecting parameters from underscore templating.

			foreach ( $options as $value => $label ) :
				?>

				<label class="sui-checkbox <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>">

					<input
						type="checkbox"
						name="<?php echo esc_attr( $name ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						<?php echo isset( $id ) ? 'id="' . esc_attr( $id . '-' . $value ) . '"' : ''; ?>
						<?php $this->render_attributes( $attributes ); ?>
						{{ _.checked( <?php echo $name; ?>.includes( '<?php echo $value; ?>' ), true ) }}
					/>

					<span aria-hidden="true"></span>
					<span><?php echo esc_html( $label ); ?></span>

				</label>

				<?php
			endforeach;

		endif;
		break;

	case 'checkbox':
		$_checked = ! $is_template ? checked( $value, $selected, false ) : '{{ _.checked( "' . $value . '", ' . $name . ' ) }}';
		?>

		<label class="sui-checkbox <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>">

			<input
				type="checkbox"
				name="<?php echo esc_attr( $name ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
				<?php echo isset( $id ) ? 'id="' . esc_attr( $id . '-' . $value ) . '"' : ''; ?>
				<?php $this->render_attributes( $attributes ); ?>
				<?php echo $_checked; ?>
			/>
			<span aria-hidden="true"></span>
			<span><?php echo wp_kses_post( $label ); ?></span>

		</label>

		<?php
		break;

	case 'inline_notice':
		// We're assuming that if there's no value, this is an inline alert notice, not a static one.
		$is_alert = empty( $value );
		?>

		<div
			<?php echo ! $is_alert ? '' : 'role="alert" aria-live="assertive"'; ?>
			<?php echo ! empty( $id ) ? 'id="' . esc_attr( $id ) . '"' : ''; ?>
			class="sui-notice <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>"
			<?php $this->render_attributes( $attributes ); ?>
		>

			<?php if ( ! $is_alert ) : ?>

				<div class="sui-notice-content">

					<div class="sui-notice-message">

						<?php if ( ! empty( $icon ) ) : ?>
							<span class="sui-notice-icon sui-icon-<?php echo esc_attr( $icon ); ?> sui-md" aria-hidden="true"></span>
						<?php endif; ?>
						<p><?php echo $value; // Make sure $value is properly escaped! We're not escaping it in here. ?></p>

					</div>

				</div>

			<?php endif; ?>

		</div>

		<?php
		break;
	default:
		$_value = ! $is_template ? $value : '{{' . $name . '}}';

		if ( isset( $icon ) ) :
			?>
			<div class="sui-control-with-icon">
		<?php endif; ?>

				<input
					type="<?php echo esc_attr( $type ); ?>"
					name="<?php echo esc_attr( $name ); ?>"
					value="<?php echo esc_attr( $_value ); ?>"
					class="sui-form-control <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>"
					<?php $this->render_attributes( $attributes ); ?>
					<?php echo isset( $id ) ? 'id="' . esc_attr( $id ) . '"' : ''; ?>
					<?php echo isset( $placeholder ) ? 'placeholder="' . esc_attr( $placeholder ) . '"' : ''; ?>
				/>

		<?php if ( isset( $icon ) ) : ?>
				<span class="sui-icon-<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></span>

			</div>
			<?php
		endif;

endswitch;
