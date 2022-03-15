require('./bootstrap');
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

let editor;

ClassicEditor
    .create( document.querySelector( '#editor' ) )
    .then(newEditor => {
        editor = newEditor;
    })
    .catch( error => {
        console.error( error );
    } );

document.querySelector( '#btnSubmit' ).addEventListener( 'click', (e) => {
    e.preventDefault();
    fetch('http://localhost/author/posts', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN':  document.querySelector("meta[name='csrf-token']").content
        },
        body: JSON.stringify(editor.getData()),
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
} );
