@extends('_layouts.layout', [
    'title' => 'Docs'
])

@section('head')
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('body')

    <div class="container mt-3">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Gestão de <b>Documentação</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <a data-bs-target="#addModal" class="btn btn-success" data-bs-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Adicionar Novo Documento</span></a>
                    </div>
                </div>
            </div>


            <div class="table-responsive">
            <table id="dt" datatable ajax-url="/api/table/folder/{{ $id }}/docs" ajax-id="doc_id" datatable-hide="-1">
                <thead>
                <tr>
                            <th dt-name="doc_id">Id</th>
                            <th dt-name="doc_name">Nome</th>
                            <th dt-name="doc_path">Path</th>
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
                    <td id="optionFilms">
                        <a id="optionObservation" option="observation" class="observation" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Observações">&#xE8F9;</i></a>
                        <a id="optionEdit" option="edit" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                        <a id="optionDelete" option="delete" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                    </td>
                </tr>
        </script>
        </div>
    </div>

 <!-- Create Modal HTML -->
 @component('_components.cardModal',[
    'id' => 'addModal',
    'class' => 'modal-success',
    'title' => 'Adicionar Documento',
    'close' => true,
 ])
    <form id="form-create">
        <input type="hidden" name="folder_id" value="{{ $id }}" />
        <div class="form-group">
            <label>Nome</label>
            <input type="text" placeholder="Descritivo Do Documento" class="form-control" name="doc_name" required>
        </div>
        <div class="form-group">
            <label>Observações</label>
            <textarea class="form-control" name="doc_observation" required></textarea>
        </div>
        <br>
        <div class="form-group">
            <label><label>File</label></label><br>
            <input type="file" class="form" name="pdf_file" id="pdf_file">
        </div>
    </form>      
    @slot('footer')
        <input type="button" class="btn btn-link" data-bs-dismiss="modal" value="Cancelar" id="cancelButton">
        <input type="submit" class="btn btn-success" form="form-create" value="Adicionar">
    @endslot
@endComponent    

<!-- Edit Modal HTML -->
@component('_components.cardModal',[
    'id' => 'modalEdit',
    'class' => 'modal-success',
    'title' => 'Editar Documento',
    'close' => true,
 ])
    <form id="form-edit">
        <input type="hidden" name="doc_id" />
        <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="doc_name" required>
        </div>
        <div class="form-group">
            <label>Observações</label>
            <textarea class="form-control" name="doc_observation" required></textarea>
        </div>
        <br>
        <div class="form-group">
            <label><label>File</label></label>
            <input type="file" class="form" name="pdf_file" id="pdf_file" required>
        </div>
    </form>      
    @slot('footer')
        <input type="button" class="btn btn-link" data-bs-dismiss="modal" value="Cancelar" id="cancelButton">
        <input type="submit" class="btn btn-success" form="form-edit" value="Adicionar">
    @endslot
@endComponent

<!-- Delete Modal HTML -->
@component('_components.cardModal', [
			'id' => 'modalDelete',
			'class' => 'modal-success',
			'title' => 'Apagar Documento',
			'close' => true
		])
    <form id="form-delete" >
		<input type="hidden" name="doc_id" />
		<div class="modal-body">
			<p>Tens a certeza que queres apagar este Documento?</p>
			<div class="form-group">
			<label>Título:</label>
			<input type="text" class="form-control" name="doc_name" disabled>
			</div>
				<p class="text-danger"><small>Esta ação não pode ser revertida.</small></p>
			</div>
				</form>
    @slot('footer')
        <input type="button" class="btn btn-link" data-bs-dismiss="modal" value="Cancelar" id="cancelButton">
        <input type="submit" class="btn btn-danger" form="form-delete" value="Apagar">
    @endslot
@endComponent

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

        let formCreateDoc = document.getElementById('form-create');
        formCreateDoc.addEventListener('submit', i => {
            i.preventDefault();
            $.ajax({
                data : new FormData(formCreateDoc),
                contentType: false,
                processData: false,
                url : "{{ route('docs.createDocs') }}",
                type : "POST",
                success : function(response) {
                    console.log(response);
                    $('#addModal').modal('hide');
                    dt.refresh();
                    toastr.success('Pasta criada com sucesso!');
                },
                error : function(error) {
                    $('#addModal').modal('hide');
                    dt.refresh();
                    toastr.error('Erro ao criar Documento!');
                } 
            });
        });

        let modalEdit = document.getElementById('modalEdit');
	    let $modalEdit = $(modalEdit);
        let formEdit = document.getElementById('form-edit');
        formEdit.addEventListener('submit', i => {
            i.preventDefault();
            $.ajax({
                data : new FormData(formEdit),
                contentType: false,
                processData: false,
                url : "{{ route('docs.editDocs') }}",
                type : "POST",
                success : function(response) {
                    console.log(response);
                    $('#modalEdit').modal('hide');    
		        	dt.refresh();
                    toastr.success('Documento editada com sucesso!');
                },
                error : function(error) {
                    toastr.error('Erro ao editar Documento!');
                    $('#modalEdit').modal('hide');    
		        	dt.refresh();
                } 
            });
        });
        let modalDelete = document.getElementById('modalDelete');
        let $modalDelete = $(modalDelete);
        let formDelete = modalDelete.querySelector('form');
        formDelete.addEventListener('submit', i => {
            i.preventDefault();
            $.ajax({
                data : $(formDelete).serialize(),
                url : "{{ route('docs.deleteDocs') }}",
                type : "POST",
                success : function(response) {
                    console.log(response);
                    $('#modalDelete').modal('hide');
                    dt.refresh();
                    toastr.success('Documento apagada com sucesso!');
                },
                error : function(error) {
                    toastr.error('Erro ao apagar Documento!');
                    $('#modalDelete').modal('hide');
                    dt.refresh();
                } 
            });
        });

        let modalObservation = document.getElementById('modalObservation');
        let $modalObservation = $(modalObservation);
        
        window.addEventListener('option-click', e => {
        let key = e.key;
        let option = e.option;
        let object = dt.ajaxJson.index[key];
        switch(option){
            case 'edit': {
            object['pdf_file:name'] = 'test';
            object['pdf_file'] = '/url';
            Utils.fill_form(modalEdit, object, true);
            $modalEdit.modal('show');
            break;
            }
            case 'delete': {
            Utils.fill_form(modalDelete, object, true);
            $modalDelete.modal('show');
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