<template>
    <div class="mt-4">
        <div>
            <file-pond
                name="image"
                ref="pond"
                label-idle="Click to choose image, or
        drag here..."
                @init="filepondIntialized"
                accepted-file-types="image/*"
                @processfile="handleProcessedFile"
                max-file-size="1MB"
            />
        </div>
        <div class="mt-8 mb-24">
            <h3 class="text-2xl font-medium text-center">Image Gallery</h3>
            <div class="grid grid-cols-4 gap-2 justify-evenly mt-4">
                <div class="flex" v-for="(image, index) in images" :key="index">
                    <button
                        class="border text-white bg-black p-1"
                        @click="deleteImage(image)"
                    >
                        X
                    </button>
                    <img :src="'/storage/images/' + image" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// Import FilePond
import vueFilePond, { setOptions } from "vue-filepond";

// Import plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm.js";
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.esm.js";

// Import styles
import "filepond/dist/filepond.min.css";

setOptions({
    server: {
        process: {
            url: "./upload",
            headers: {
                "x-CSRF-TOKEN": document.head.querySelector(
                    'meta[name="csrf_token"]'
                ).content,
            },
        },
    },
});

// Create FilePond component
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize
);
export default {
    components: {
        FilePond,
    },
    data() {
        return {
            images: [],
        };
    },
    mounted() {
        this.getImages();
    },
    methods: {
        getImages() {
            axios
                .get("images")
                .then((response) => {
                    this.images = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        deleteImage(image) {
            if (confirm("Delete")) {
                axios
                    .delete("images/delete/" + `${image}`)
                    .then((res) => {
                        this.getImages();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        filepondIntialized() {
            console.log("filepond is ready", this.$refs.pond);
        },
        handleProcessedFile(error, file) {
            if (error) {
                console.log(error);
                return;
            }

            this.getImages();
        },
    },
};
</script>
