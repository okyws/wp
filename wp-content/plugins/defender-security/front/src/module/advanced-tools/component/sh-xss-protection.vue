<template>
    <div class="sui-toggle-content">
        <span class="sui-description toogle-content-description">
            {{ __( "Choose what level of protection X-XSS protection you would like to apply when XSS attacks are detected." ) }}
        </span>
        <div class="sui-side-tabs">
            <div class="sui-tabs-menu">
                <label for="xss-sanitize" class="sui-tab-item" :class="{active:model.sh_xss_protection_mode==='sanitize'}">
                    <input type="radio" name="sh_xss_protection_mode" value="sanitize" v-model="model.sh_xss_protection_mode"
                           id="xss-sanitize"
                           data-tab-menu="xss-sanitize-box">
                    {{__("Sanitize")}}
                </label>
                <label for="xss-block" class="sui-tab-item" :class="{active:model.sh_xss_protection_mode==='block'}">
                    <input type="radio" name="sh_xss_protection_mode" value="block" v-model="model.sh_xss_protection_mode"
                           id="xss-block"
                           data-tab-menu="xss-block-box">
                    {{__("Block")}}
                </label>
            </div>

            <div class="sui-tabs-content">
                <div class="sui-tab-content sui-tab-boxed" id="xss-sanitize-box"
                     :class="{active:model.sh_xss_protection_mode==='sanitize'}"
                     data-tab-content="xss-sanitize-box">
                    <p class="sui-p-small">
                        {{__("If a cross-site scripting attack is detected, the browser will sanitize the page (remove the unsafe parts).")}}
                    </p>
                </div>
                <div class="sui-tab-content sui-tab-boxed" id="xss-block-box"
                     :class="{active:model.sh_xss_protection_mode==='block'}"
                     data-tab-content="xss-allow-from-box">
                    <span class="sui-description">
                        {{__("Enables XSS filtering. Rather than sanitizing the page, the browser will prevent rendering of the page if an attack is detected.")}}
                    </span>
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
        name: "sh_xss_protection",
        data: function () {
            return {
                state: {
                    on_saving: false
                },
                mode: this.misc.mode,
                model: this.model
            }
        }
    }
</script>