<template>
    <div class="sui-toggle-content">
        <span class="sui-description toogle-content-description">
            {{__( "Choose an option that matches your requirements from the options below to prevent unwanted actions when your webpages are embedded elsewhere.")}}
        </span>

        <div class="sui-side-tabs">
            <div class="sui-tabs-menu">
                <label for="fp-site" class="sui-tab-item" :class="{active:model.sh_feature_policy_mode==='self'}">
                    <input type="radio" name="model.sh_feature_policy_mode" value="self" v-model="model.sh_feature_policy_mode"
                           id="fp-site"
                           data-tab-menu="fp-site-box">
                    {{__("On site & iframe")}}
                </label>
                <label for="fp-allow" class="sui-tab-item" :class="{active:model.sh_feature_policy_mode==='allow'}">
                    <input type="radio" name="model.sh_feature_policy_mode" value="allow" v-model="model.sh_feature_policy_mode"
                           id="fp-allow"
                           data-tab-menu="fp-allow-box">
                    {{__("All")}}
                </label>
                <label for="fp-origins" class="sui-tab-item" :class="{active:model.sh_feature_policy_mode==='origins'}">
                    <input type="radio" name="model.sh_feature_policy_mode" value="origins" v-model="model.sh_feature_policy_mode"
                           id="fp-origins"
                           data-tab-menu="fp-origins-box">
                    {{__("Specific Origins")}}
                </label>
                <label for="fp-none" class="sui-tab-item" :class="{active:model.sh_feature_policy_mode==='none'}">
                    <input type="radio" name="model.sh_feature_policy_mode" value="none" v-model="model.sh_feature_policy_mode"
                           id="fp-none"
                           data-tab-menu="fp-none-box">
                    {{__("None")}}
                </label>
            </div>

            <div class="sui-tabs-content">
                <div class="sui-tab-content sui-tab-boxed" id="fp-site-box"
                     :class="{active:model.sh_feature_policy_mode==='self'}"
                     data-tab-content="fp-site-box">
                    <p class="sui-p-small">
                        {{__("The page can only be displayed in a frame on the same origin as the page itself. The spec leaves it up to browser vendors to decide whether this option applies to the top level, the parent, or the whole chain.")}}
                    </p>
                </div>
                <div class="sui-tab-content sui-tab-boxed" id="fp-allow-box"
                     :class="{active:model.sh_feature_policy_mode==='allow'}"
                     data-tab-content="fp-allow-box">
                    <p class="sui-p-small">
                        {{__("The feature will be allowed in this document, and all nested browsing contexts (iframes) regardless of their origin.")}}
                    </p>
                </div>
                <div class="sui-tab-content sui-tab-boxed" id="fp-origins-box"
                     :class="{active:model.sh_feature_policy_mode==='origins'}"
                     data-tab-content="fp-origins-box">
                    <div class="sui-form-field">
                        <label class="sui-label">{{__("Origin URL")}}</label>
                        <textarea class="sui-form-control"
                                  name="sh_feature_policy_urls"
                                  v-model="model.sh_feature_policy_urls"
                              :placeholder="__('Place URLs here, one per line')"></textarea>
                        <span class="sui-description" v-html="tabUrlsText"></span>
                    </div>
                </div>
                <div class="sui-tab-content sui-tab-boxed" id="fp-none-box"
                     :class="{active:model.sh_feature_policy_mode==='none'}"
                     data-tab-content="fp-none-box">
                    <p class="sui-p-small">
                        {{__("The feature is disabled in top-level and nested browsing contexts.")}}
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
		name: "sh-feature-policy",
		data: function () {
			return {
                misc: this.misc,
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
			this.tabUrlsText = vsprintf( this.__('The feature is allowed for specific origins. Place URLs here %s, one per line.'), '<strong>https://example.com</strong>' );
		}
	}
</script>