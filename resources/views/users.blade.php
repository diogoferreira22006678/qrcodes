@extends('_layouts.layout', [
    'title' => 'Users',
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
                        <h2>Gestão de <b>Usuários</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <a data-bs-target="#addModal" class="btn btn-success" data-bs-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Adicionar Novo Utilizador</span></a>
                    </div>
                </div>
            </div>


            <div class="table-responsive">
            <table id="dt" datatable ajax-url="/api/table/users" ajax-id="user_id" datatable-hide="-1">
                <thead>
                <tr>
                            <th dt-name="user_id">Id</th>
                            <th dt-name="user_name">Nome</th>
                            <th dt-name="user_super">Admin</th>
                            <th dt-name="user_ad">Interno</th>
                            <th>Ações</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
            <script id="dt-template" type="text/template">
                <tr option-key="${user_id}">
                    <td>${user_id}</td>
                    <td>${user_name}</td>
                    <td>${user_super == 1 ? 'Sim' : 'Não'}</td>
                    <td>${user_ad == 1 ? 'Sim' : 'Não'}</td>
                    <td id="optionFilms">
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
    'title' => 'Adicionar Utilizador',
    'close' => true,
 ])
    <form id="form-create">
        <div class="form-group">
            <label>UserName</label>
            <input type="text" class="form-control" name="user_name" required>
        </div>
        <br>
        <div class="form-group">
            <label><label>Palavra-Passe</label></label><br>
            <input type="pasword" class="form-control" name="user_pass" id="user_pass">
        </div>
        <br>
        <div class="form-group">
            <label><label>Admin</label></label><br>
            <input type="checkbox" class="form" name="user_super" id="user_super">
        </div>
        <div class="form-group">
            <label><label>Interno</label></label><br>
            <input type="checkbox" class="form" name="user_ad" id="user_ad">
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
    'title' => 'Editar Utilizado',
    'close' => true,
 ])
    <form id="form-edit">
        <input type="hidden" name="user_id" />
        <div class="form-group">
            <label>UserName</label>
            <input type="text" class="form-control" name="user_name" required>
        </div>
        <br>
        <div class="form-group">
            <label><label>Palavra-Passe</label></label><br>
            <input type="pasword" class="form-control" name="user_pass" id="user_pass" placeholder="(inalterada)">
        </div>
        <br>
        <div class="form-group">
            <label><label>Admin</label></label><br>
            <input type="checkbox" class="form" name="user_super" id="user_super">
        </div>
        <div class="form-group">
            <label><label>Interno</label></label><br>
            <input type="checkbox" class="form" name="user_ad" id="user_ad">
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
			'title' => 'Apagar Utilizador',
			'close' => true
		])
    <form id="form-delete" >
		<input type="hidden" name="user_id" />
		<div class="modal-body">
			<p>Tens a certeza que queres apagar este Utilizador?</p>
			<div class="form-group">
			<label>Utilizador:</label>
			<input type="text" class="form-control" name="user_name" disabled>
			</div>
			<p class="text-danger"><small>Esta ação não pode ser revertida.</small></p>
		</div>
	</form>
    @slot('footer')
        <input type="button" class="btn btn-link" data-bs-dismiss="modal" value="Cancelar" id="cancelButton">
        <input type="submit" class="btn btn-danger" form="form-delete" value="Apagar">
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
                url : "{{ route('users.createUsers') }}",
                type : "POST",
                success : function(response) {
                    console.log(response);
                    $('#addModal').modal('hide');
                    dt.refresh();
                    toastr.success('Utilizador criada com sucesso!');
                },
                error : function(error) {
                    $('#addModal').modal('hide');
                    dt.refresh();
                    toastr.error('Erro ao criar Utilizador!');
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
                url : "{{ route('users.editUsers') }}",
                type : "POST",
                success : function(response) {
                    console.log(response);
                    $('#modalEdit').modal('hide');    
		        	dt.refresh();
                    toastr.success('Utilizador editado com sucesso!');
                },
                error : function(error) {
                    toastr.error('Erro ao editar Utilizador!');
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
                url : "{{ route('users.deleteUsers') }}",
                type : "POST",
                success : function(response) {
                    console.log(response);
                    $('#modalDelete').modal('hide');
                    dt.refresh();
                    toastr.success('Utilizador apagado com sucesso!');
                },
                error : function(error) {
                    toastr.error('Erro ao apagar Utilizador!');
                    $('#modalDelete').modal('hide');
                    dt.refresh();
                } 
            });
        });
        
        window.addEventListener('option-click', e => {
        let key = e.key;
        let option = e.option;
        let object = dt.ajaxJson.index[key];
        switch(option){
            case 'edit': {
            object.user_pass = '';
            Utils.fill_form(modalEdit, object, true);
            $modalEdit.modal('show');
            break;
            }
            case 'delete': {
                
            Utils.fill_form(modalDelete, object, true);
            $modalDelete.modal('show');
            break;
            }
        }
        });
    </script>
@endsection