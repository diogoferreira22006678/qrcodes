@extends('_layouts.layout', [
    'title' => 'Docs',
    'simple' => true
])

@section('head')
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('body')

<!-- Table with all the docs from the folder -->
<div class="container mt-3">
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-6">
                    <h2>{{ $name }}</b></h2>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="dt" datatable ajax-url="/api/table/folder/{{ $id }}/docs" ajax-id="doc_id" datatable-hide="-1">
                <thead>
                    <tr>
                        <th dt-name="doc_id">Id</th>
                        <th dt-name="doc_name">Nome</th>
                        <th dt-name="doc_local">Path</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <script id="dt-template" type="text/template">
            <tr option-key="${doc_id}">
                <td>${doc_id}</td>
                <td>${doc_name}</td>
                <td>${doc_path}</td>
                <td id="optionFilms">  <a id="optionObservation" option="observation" class="observation" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Observações">&#xE8F9;</i></a>
                  <a id="optionViewPdf" option = "optionViewPdf" href="/qr/${folder_id}/${doc_path}" ><i class="material-icons" data-toggle="tooltip" title="View">&#xE417;</i></a>
                </td>
            </tr>
        </script>
    </div>

<!-- Observation Modal HTML -->
@component('_components.cardModal' , [
    'id' => 'modalObservation',
    'class' => 'modal-success',
    'title' => 'Observações',
    'close' => true,
 ])
    <form id="form-delete" >
		<input type="hidden" name="doc_id" />
		<div class="modal-body">
            <div class="form-group">
                <label>Observações:</label>
                <input class="form-control" name="doc_observation" disabled>
            </div>
		</div>
	</form>
    @slot('footer')
    @endslot
@endComponent

@endsection 

@section('scripts')

<script>

let modalObservation = document.getElementById('modalObservation');
let $modalObservation = $(modalObservation);

window.addEventListener('option-click', e => {
    let key = e.key;
    let option = e.option;

    let object = dt.ajaxJson.index[key];

    switch(option){
     
        case 'optionViewPdf' : {
            // Open the pdf in a new tab
            $.ajax({
                    data : object,
                    url : "{{ route('pdf.getPdf') }}",
                    type: 'GET',
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error: ' + errorThrown);
                    }
            });
        break;
        }
        case 'observation': {
            Utils.fill_form(modalObservation, object, true);
            $modalObservation.modal('show');
            break;
            }
    }
    });
</script>
@endsection