window.listingForm = function () {
    return {
        previews: [],
        fileList: [],

        handleImages(e) {
            const newFiles = Array.from(e.target.files);
            const remaining = 6 - this.previews.length;
            newFiles.slice(0, remaining).forEach((file) => {
                const reader = new FileReader();
                reader.onload = (ev) => this.previews.push(ev.target.result);
                reader.readAsDataURL(file);
                this.fileList.push(file);
            });
            e.target.value = "";
            this.syncFiles();
        },

        removeImage(index) {
            this.previews.splice(index, 1);
            this.fileList.splice(index, 1);
            this.syncFiles();
        },

        syncFiles() {
            const dt = new DataTransfer();
            this.fileList.forEach((f) => dt.items.add(f));
            const input = document.getElementById("real-file-input");
            if (input) input.files = dt.files;
        },
    };
};
