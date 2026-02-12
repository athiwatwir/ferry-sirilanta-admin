@extends('layouts.default')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

<x-card>
    <x-form :action="route('informationText.store')">

        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.input name="title" label="Title" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.selection name="position" label="Position" :options="$positions" />
            </div>

            <div class="col-12 mb-3">
                <label for="editor" class="form-label">Body</label>
                <div id="editor" style="min-height: 200px;">

                </div>
                <input type="hidden" name="body" id="body">
            </div>
        </div>
    </x-form>
</x-card>


@stop

@section('styles')
<style>
    #editor {
        border: var(--bs-border-width) solid color-mix(in sRGB, var(--bs-base-color) 22%, var(--bs-paper-bg));
        border-radius: var(--bs-border-radius);
    }

    #editor .ql-toolbar {
        border-top-left-radius: var(--bs-border-radius);
        border-top-right-radius: var(--bs-border-radius);
        border-bottom: var(--bs-border-width) solid color-mix(in sRGB, var(--bs-base-color) 22%, var(--bs-paper-bg));
    }

    #editor .ql-container {
        border-bottom-left-radius: var(--bs-border-radius);
        border-bottom-right-radius: var(--bs-border-radius);
    }

    #editor:focus-within {
        border-color: var(--bs-primary);
        box-shadow: 0 0.125rem 0.375rem 0 rgba(var(--bs-primary-rgb), 0.3);
    }

</style>
@stop

@section('script')

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    const quill = new Quill('#editor', {
        theme: 'snow'
        , modules: {
            toolbar: [
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }]

                , [{
                    'size': []
                }]
                , ['bold', 'italic', 'underline', 'strike']
                , [{
                    'color': []
                }, {
                    'background': []
                }]
                , [{
                    'script': 'sub'
                }, {
                    'script': 'super'
                }]
                , [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }]
                , [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }]
                , [{
                    'align': []
                }]
                , ['link', 'image']
                , ['clean']
            ]
        }
    });

    // Set initial value
    document.getElementById('body').value = quill.root.innerHTML;

    // Update hidden input when editor content changes
    quill.on('text-change', function() {
        document.getElementById('body').value = quill.root.innerHTML;
    });

    // Also update on form submit to ensure latest value
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            document.getElementById('body').value = quill.root.innerHTML;
        });
    }

</script>

@stop
