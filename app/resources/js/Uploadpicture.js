if (typeof window.Uploadpicture === 'undefined') {
    class Uploadpicture {
        archivo(evt, id) {
            let files = evt.target.files; // FileList object
            let f = files[0];
            if (f.type.match('image.*')) {
                let reader = new FileReader();
                reader.onload = ((theFile) => {
                    return (e) => {
                        document.getElementById(id).innerHTML = ['<img class="responsive-img" src="',
                            e.target.result, '" title="', escape(theFile.name), '"/>'
                        ].join('');
                    };
                })(f);
                reader.readAsDataURL(f);
            }
        }
    }
    window.Uploadpicture = Uploadpicture;
}