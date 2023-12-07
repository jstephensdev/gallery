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
                        class="p-1 mt-0 pt-0 font-bold absolute hover:bg-blue-700"
                        @click="deleteImage(image)"
                    >
                        X
                    </button>
                    <img :src="'/storage/images/' + image.name" />
                </div>
            </div>
            <!-- <div class="flex mt-8 justify-evenly">
                <span>{{ `page ${current_page} of ${last_page}` }}</span>
                <a :href="previousUrl">
                    <button
                        :disabled="isPreviousDisabled"
                        @click="handlePreviousPage"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Previous
                    </button>
                </a>
                <a :href="nextUrl">
                    <button
                        :disabled="isNextDisabled"
                        @click="handleNextPage"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Next
                    </button>
                </a>
            </div> -->
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
            current_page: 0,
            last_page: 0,
            images: {},
            isPreviousDisabled: false,
            isNextDisabled: false,
            prev_page_url: '',
            next_page_url:  ''
        };
    },
    mounted() {
        this.getImages();
    },
    computed: {
        // isPreviousDisabled() {
        //     return this.current_page <= 1;
        // },
        // isNextDisabled() {
        //     return this.current_page === this.last_page;
        // },
        // previousUrl() {
        //     return this.prev_page_url;
        // },
        // nextUrl() {
        //     return this.next_page_url;
        // },
    },
    methods: {
        getImages() {
            axios
                .get("images")
                .then((response) => {
                    console.log(response.data);
                    // this.current_page = response.data.from;
                    // this.last_page = response.data.last_page;
                    this.images = response.data.data;
                    // this.links = response.data.links;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        deleteImage(image) {
            if (confirm("Delete")) {
                axios
                    .delete("images/delete/" + `${image}`)
                    .then(() => {
                        this.getImages();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        // handlePreviousPage() {
        //     this.current_page = this.current_page - 1;
        // },
        // handleNextPage() {
        //     this.current_page = this.current_page + 1;
        // },
        filepondIntialized() {
            console.log("filepond is ready", this.$refs.pond);
        },
        handleProcessedFile(error) {
            if (error) {
                console.log(error);
                return;
            }

            this.getImages();
        },
    },
};
</script>
