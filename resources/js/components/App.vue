<template>
    <div class="mt-4">
        <file-pond
            name="image"
            ref="pond"
            label-idle="Click to choose image, or
        drag here..."
            server="/upload"
            @init="filepondIntialized"
            accepted-file-types="image/*"
        />
    </div>
</template>

<script>
// Import FilePond
import vueFilePond from "vue-filepond";

// Import plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm.js";

// Import styles
import "filepond/dist/filepond.min.css";

// Create FilePond component
const FilePond = vueFilePond(FilePondPluginFileValidateType);
export default {
    components: {
        FilePond,
    },
    data() {
        return {};
    },
    methods: {
        filepondIntialized() {
            console.log("filepond is ready", this.$refs.pond);
        },
    },
};
</script>

<style></style>
