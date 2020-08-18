<template>
	<div class="sui-toggle-content">
		<span class="sui-description toogle-content-description">
			{{ __( 'Choose whether or not you want to allow your webpages to be embedded inside iframes.' ) }}
		</span>
		<div class="sui-side-tabs">
			<div class="sui-tabs-menu">
				<label for="xf-sameorigin" class="sui-tab-item" :class="{active:model.sh_xframe_mode==='sameorigin'}">
					<input type="radio" name="sh_xframe_mode" value="sameorigin" v-model="model.sh_xframe_mode"
						id="xf-sameorigin"
						data-tab-menu="xf-sameorigin-box">
					{{__("Sameorigin")}}
				</label>
				<label for="xf-allow-from" class="sui-tab-item" :class="{active:model.sh_xframe_mode=='allow-from'}">
					<input type="radio" name="sh_xframe_mode" value="allow-from" v-model="model.sh_xframe_mode"
						id="xf-allow-from"
						data-tab-menu="xf-allow-from-box">
					{{__("Allow-from")}}
				</label>
				<label for="xf-deny" class="sui-tab-item" :class="{active:model.sh_xframe_mode==='deny'}">
					<input type="radio" name="sh_xframe_mode" value="deny" v-model="model.sh_xframe_mode"
						id="xf-deny"
						data-tab-menu="xf-deny-box">
					{{__("Deny")}}
				</label>
			</div>

			<div class="sui-tabs-content">
				<div class="sui-tab-content sui-tab-boxed" id="xf-sameorigin-box"
					:class="{active:model.sh_xframe_mode==='sameorigin'}"
					data-tab-content="xf-sameorigin-box">
					<p class="sui-p-small">
						{{__("The page can only be displayed in a frame on the same origin as the page itself. The spec leaves it up to browser vendors to decide whether this option applies to the top level, the parent, or the whole chain.")}}
					</p>
				</div>
				<div class="sui-tab-content sui-tab-boxed" id="xf-allow-from-box"
					:class="{active:model.sh_xframe_mode==='allow-from'}"
					data-tab-content="xf-allow-from-box">
					<div class="sui-form-field">
						<label class="sui-label">{{__("Allow from URLs")}}</label>
						<textarea class="sui-form-control"
							name="sh_xframe_urls"
							v-model="model.sh_xframe_urls"
							:placeholder="__('Place allowed page URLs, one per line')"></textarea>
						<span class="sui-description" v-html="tabUrlsText"></span>
					</div>
				</div>
				<div class="sui-tab-content sui-tab-boxed" id="xf-deny-box"
					:class="{active:model.sh_xframe_mode==='deny'}"
					data-tab-content="xf-deny-box">
					<p class="sui-p-small">
						{{__("The page can’t be displayed in a frame, regardless of the site attempting to do so.")}}
					</p>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import helper from '../../../helper/base_hepler';

	export default {
		mixins: [helper],
		props: ['misc', 'model'],
		name: "sh-xframe",
		data: function () {
			return {
				state: {
					on_saving: false
				},
				mode: this.misc.mode,
				values: this.misc.values,
				model: this.model,
				tabUrlsText: ''
			}
		},
		created: function () {
			this.tabUrlsText = vsprintf( this.__('The page <strong>%s</strong> will only be displayed in a frame on the specified origin. One per line.'), this.siteUrl );
		}
	}
</script>