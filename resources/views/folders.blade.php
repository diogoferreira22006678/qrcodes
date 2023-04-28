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
                        <h2>Pastas de <b>Documentação</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <a data-bs-target="#addModal" class="btn btn-success" data-bs-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Adicionar Nova Pasta</span></a>
                    </div>
                </div>
            </div>


            <div class="table-responsive">
            <table id="dt" datatable ajax-url="/api/table/folders" ajax-id="folder_id" datatable-hide="-1">
                <thead>
                <tr>
                            <th dt-name="folder_id">Id</th>
                            <th dt-name="folder_name">Nome</th>
                            <th dt-name="folder_local">Local</th>
                            <th>Ações</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
            <script id="dt-template" type="text/template">
                <tr option-key="${folder_id}">
                    <td>${folder_id}</td>
                    <td>${folder_name}</td>
                    <td>${folder_local}</td>
                    <td id="optionFilms">
                        <a id="optionQrCode" option="qrcode" class="view" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="View QR Code">qr_code</i></a>
                        <a id="optionViewUser" href="/qr/${folder_id}"><i class="material-icons" data-toggle="tooltip" title="User View">link</i></a>
                        <a id="optionViewFolder" href="/admin/qrcodes/${folder_id}/docs"><i class="material-icons" data-toggle="tooltip" title="Admin View">&#xE417;</i></a>
                        <a id="optionEdit" option="edit" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                        <a id="optionDelete" option="delete" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                    </td>
                </tr>
        </script>
        </div>
    </div>
<!-- Create QrCode Modal HTML -->
@component('_components.cardModal',[
        'id' => 'qrCodeModal',
        'class' => 'modal-success',
        'title' => 'QR Code',
        'close' => true,
     ])
     <form id = "form-qr">
        <div class="form-group d-flex flex-wrap justify-content-center">
            <canvas id="canvas" style="max-width:100%"></canvas>
            <canvas id="canvas-obs" style="max-width:100%"></canvas>
        </div>
     </form>
    @slot('footer')
        <input type="button" class="btn btn-link" data-bs-dismiss="modal" value="Cancelar" id="cancelButton"/>
        <a target="_blank" class="btn btn-success" id="download-qr">Download</a>
    @endslot
@endcomponent

 <!-- Create Modal HTML -->
 @component('_components.cardModal',[
    'id' => 'addModal',
    'class' => 'modal-success',
    'title' => 'Adicionar Pasta',
    'close' => true,
 ])
    <form id="form-create">
        <div class="form-group">
            <label>Nome</label>
            <input type="text" placeholder="Nome Da Pasta" class="form-control" name="folder_name" required>
        </div>
        <div class="form-group">
            <label>Local</label>
            <input type="text"  class="form-control" name="folder_local" required>
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea class="form-control" placeholder="Descritivo Da Pasta" name="folder_description" maxlength="200" required></textarea>
        </div>
        <div class="form-group">
            <label><label>Categoria</label></label><br>
        @component('_components.formSelect', [
            'required' => true,
            'class' => '',
            'attributes' => 'ajax-url="/api/select/categories" ',
            'name' => 'category_id',
            'placeholder' => 'Escolhe a Categoria',
            'array' => [],
            'key' => 'id',
            'value' => 'title',
          ])@endComponent
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
    'title' => 'Editar Pasta',
    'close' => true,
 ])
    <form id="form-edit">
        <input type="hidden" name="folder_id" />
        <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="folder_name" required>
        </div>
        <div class="form-group">
            <label>Local</label>
            <input type="text"  class="form-control" name="folder_local" required>
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea class="form-control" name="folder_description" maxlength="200" required></textarea>
        </div>
        <div class="form-group">
            <label><label>Categoria</label></label><br>
        @component('_components.formSelect', [
            'required' => true,
            'class' => '',
            'attributes' => 'ajax-url="/api/select/categories" fill=categories:category_id|category_name',
            'name' => 'category_id',
            'placeholder' => 'Escolhe a Categoria',
            'array' => [],
            'key' => 'id',
            'value' => 'title',
          ])@endComponent
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
			'title' => 'Apagar Pasta',
			'close' => true
		])
    <form id="form-delete" >
		<input type="hidden" name="folder_id" />
		<div class="modal-body">
			<p>Tens a certeza que queres apagar esta Pasta?</p>
			<div class="form-group">
			<label>Título:</label>
			<input type="text" class="form-control" name="folder_name" disabled>
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
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="/lib/qr.js"></script>
    <script>
        let canvas = document.getElementById('canvas');
        let canvasObs = document.getElementById('canvas-obs');

        let formCreateDoc = document.getElementById('form-create');
        formCreateDoc.addEventListener('submit', i => {
            i.preventDefault();
            $.ajax({
                data : $(formCreateDoc).serialize(),
                url : "{{ route('folders.createFolders') }}",
                type : "POST",
                success : function(response) {
                    console.log(response);
                    $('#addModal').modal('hide');
                    dt.refresh();
                    toastr.success('Pasta criada com sucesso!');
                },
                error : function(error) {
                    console.log(error);
                    alert('Erro ao criar pasta!' + error.statusText);
                    toastr.error('Erro ao criar pasta!');
                } 
            });
        });

        let modalEdit = document.getElementById('modalEdit');
	    let $modalEdit = $(modalEdit);
        let formEdit = document.getElementById('form-edit');
        formEdit.addEventListener('submit', i => {
            i.preventDefault();
            $.ajax({
                data : $(formEdit).serialize(),
                url : "{{ route('folders.editFolders') }}",
                type : "POST",
                success : function(response) {
                    console.log(response);
                    $('#modalEdit').modal('hide');    
		        	dt.refresh();
                    toastr.success('Pasta editada com sucesso!');
                },
                error : function(error) {
                    console.log(error);
                    alert('Erro ao editar pasta!' + error.statusText);
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
                url : "{{ route('folders.deleteFolders') }}",
                type : "POST",
                success : function(response) {
                    console.log(response);
                    $('#modalDelete').modal('hide');
                    dt.refresh();
                    toastr.success('Pasta apagada com sucesso!');
                },
                error : function(error) {
                    console.log(error);
                    alert('Erro ao apagar pasta!' + error.statusText);
                } 
            });
        });
        
        window.addEventListener('option-click', e => {
        let key = e.key;
        let option = e.option;
        let object = dt.ajaxJson.index[key];
        switch(option){
            case 'edit': {
            Utils.fill_form(modalEdit, object, true);
            $modalEdit.modal('show');
            break;
            }
            case 'delete': {
                
            Utils.fill_form(modalDelete, object, true);
            $modalDelete.modal('show');
            break;
            }

            case 'qrcode' : {
                genQR("{{ env('APP_URL') }}" + '/qr/' + object.folder_id, canvas, 10);
                createQRUrl();

                let extra_width = 600;
                canvasObs.width = canvas.width + extra_width;
                canvasObs.height = canvas.height;
                let ctx = canvasObs.getContext("2d");
                ctx.fillStyle = "#ffffff";
                ctx.fillRect(0, 0, canvasObs.width, canvasObs.height);
                ctx.drawImage(canvas, 0, 0);
                
                let obs_text = object.folder_description;
                // draw text on canvas next to the image
                let font_size = 30;
                let font_margin = 5;
                ctx.font = font_size + "px Arial";
                ctx.fillStyle = "#000000";

                
                let padded_width = extra_width - 20;
                let words = obs_text.split(' ');
                let lines = [];
                line = '';
                y = 20;
                for (let n = 0; n < words.length; n++) {
                    let testLine = line + words[n] + ' ';
                    let metrics = ctx.measureText(testLine);
                    let testWidth = metrics.width;
                    if (testWidth > padded_width && n > 0) {
                        lines.push(line);
                        line = words[n] + ' ';
                    } else {
                        line = testLine;
                    }
                }
                lines.push(line);

                // now draw the lines in the array vertically centered
                let verticalY = ((canvas.height - (lines.length * (font_size + font_margin))) / 2) + font_size/2;
                for (let n = 0; n < lines.length; n++) {
                    ctx.fillText(lines[n], canvas.width + 10, verticalY + font_size/2);
                    verticalY += font_size + font_margin;
                }




                $('#qrCodeModal').modal('show');
                break;
            }
        }
        });

        function createQRUrl(){
            let blob = canvas.toBlob(function(blob) {
                let url = URL.createObjectURL(blob);
                let a = document.getElementById('download-qr');
                a.href = url;
            });
        }

    </script>
@endsection