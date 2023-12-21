@extends('dashboard')


@section('contenido')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    <div class="container">
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

    @if(session('error'))
            <div class="alert alert-danger">
                <ul>
                    <li>{{ session('error') }}</li>
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <ul>
                    <li>{{ session('success') }}</li>
                </ul>
            </div>
        @endif

        <form action="{{ route('registerTask') }}" method="post">
            @csrf
            <label for="">Crear tarea nueva</label>
            <div class="mb-3">
                <input type="text" class="form-control" name="name" id="title" placeholder="Nombre">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="description" id="description" placeholder="description">
            </div>
            <div class="mb-3">
                <input type="date" class="form-control" name="dateEnd" id="dateEnd" placeholder="Fecha fin">
            </div>
            <div class="mb-3">
                <select class="form-control" name="user" id="user">
                    <option value=""> selecciona un usuario </option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"> {{ $user->name }} </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <select class="form-control" name="project" id="project">
                    <option value=""> selecciona un projecto </option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}"> {{ $project->title }} </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary" type="submit">Registrar tarea</button>
        </form>
    </div>
</body>

<br>
<br>
<br>
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>Id tarea</th>
                    <th>Nombre tarea</th>
                    <th>Descripcion tarea</th>
                    <th>Fecha fin</th>
                    <th>Estado</th>
                    <th>Usuario</th>
                    <th>Proyecto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $dat)
                    <tr>
                        <td> {{ $dat->id }} </td>
                        <td> {{ $dat->name }} </td>
                        <td> {{ $dat->description }} </td>
                        <td> {{ $dat->dateEnd }} </td>
                        <td>
                            <?php
                                if($dat->status == '1'){
                                    echo('Pendiente');
                                }elseif ($dat->status == '2') {
                                    echo('En progreso');
                                }else{
                                    echo('Completada');
                                }
                            ?>
                        </td>
                        <td> {{ $dat->userId }} </td>
                        <td> {{ $dat->project }} </td>
                        <td> <button class="btn btn-success" onclick="editTask('{{$dat->id}}', '{{$dat->name}}', '{{$dat->description}}', '{{$dat->dateEnd}}', '{{$dat->status}}', '{{$dat->userId}}', '{{$dat->project}}');" >Editar</button> <button class="btn btn-danger" onclick="deleteTask('{{$dat->id}}')" >Eliminar</button> <button class="btn btn-success" onclick="comments('{{$dat->id}}');">Agregar comentarios</button> <button class="btn btn-secondary" onclick="see('{{$dat->id}}');">Trazabilidad</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="commentsTask" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Trazabilidad y comentarios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Agregar comentarios</label>
                    <form action="{{ route('commentTask') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="">Id tarea</label>
                            <input type="text" class="form-control" name="id" id="idps" value="" readonly>
                        </div>
                        <div class="mb-3">
                        <label for="">Comentario</label>
                            <input type="text" class="form-control" name="description" id="descriptionp" placeholder="description">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Guardar comentario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="traceabilityTask" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Comentarios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Id tarea</th>
                                <th scope="col">Autor</th>
                                <th scope="col">Descripcion comentario</th>
                                <th scope="col">Fecha de creacion</th>
                            </tr>
                        </thead>
                        <tbody id="tableComments"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="detailTask" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar tarea # <label id="taskNumber"></label></h5>
        </div>
        <div class="modal-body">
            <form action="{{ route('updateTask') }}" method="post">
                @csrf
                <div class="mb-3">
                    <input type="text" class="form-control" name="id" id="idp" value="" readonly>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="name" id="namet" placeholder="Nombre" readonly>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="description" id="descriptiont" placeholder="description" readonly>
                </div>
                <div class="mb-3">
                    <input type="date" class="form-control" name="dateEnd" id="dateEndt" placeholder="Fecha fin" readonly>
                </div>

                <div class="mb-3">
                    <select class="form-control" name="userId" id="userId" readonly>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"> {{ $user->name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <select class="form-control" name="project" id="project" readonly>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}"> {{ $project->title }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <select class="form-control" name="status" id="status">
                        <option value=""> selecciona un estado</option>
                        <option value="2">En progreso</option>
                        <option value="3">Completada</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Editar tarea</button>
                </div>
            </form>
        </div>
        </div>
        <div class="modal-body">


        </div>
        </div>

    <button type="button" id ="detail"class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailTask" style="display:none;"></button>
    <button type="button" id ="comments"class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#commentsTask" style="display:none;"></button>
    <button type="button" id ="traceability"class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#traceabilityTask" style="display:none;"></button>


</html>
@endsection

<script>
    function editTask(id, name, description, dateEnd, status, userId, project){
        let taskNumber = document.getElementById('taskNumber');
        taskNumber.innerHTML = id;

        let idp = document.getElementById('idp');
        idp.value = id;

        let namet = document.getElementById('namet');
        namet.value = name;

        let descriptiont = document.getElementById('descriptiont');
        descriptiont.value = description;

        let dateEndt = document.getElementById('dateEndt');
        dateEndt.value = dateEnd;

        let detail = document.getElementById('detail');
        detail.click();
    }

    function comments(id){

        let idp = document.getElementById('idps');
        idp.value = id;

        let detail = document.getElementById('comments');
        detail.click();
    }

    function validartoken() {
        token = $('#token').val();
        return token;
    }

    function see(id){
        let idp = document.getElementById('idp');
        idp.value = id;

        let detail = document.getElementById('traceability');
        detail.click();

        formData = new FormData();
        formData.append('id',id);
        token = validartoken();
        formData.append('_token',token);

        $.ajax({
            type:"POST",
            url:   "{{ route('traceability') }}",
            data : formData,
            processData : false,
            contentType : false,
            success : function(result, status, xhr){
                if(result.code == 200){
                    var html = '';
                    var i;
                    var data = result.Comments;
                    for (i = 0; i < data.length; i++) {
                    html += '<tr>' +
                        '<td>' + data[i].idTask + '</td>' +
                        '<td>' + data[i].name + '</td>' +
                        '<td>' + data[i].description + '</td>' +
                        '<td>' + data[i].date + '</td>' +
                        '</tr>';
                    }
                    $('#tableComments').html(html);
                }else{
                    alert("error")
                }

            }
        });
    }

    function deleteTask(id) {
        formData = new FormData();
        formData.append('id',id);
        token = validartoken();
        formData.append('_token',token);

        $.ajax({
            type:"POST",
            url:   "{{ route('deleteTask') }}",
            data : formData,
            processData : false,
            contentType : false,
            success : function(result, status, xhr){
                if(result.code == 200){
                    location.reload();
                    var html = '';
                    var i;
                    var data = result.success;
                    html += '<ul>' +
                        '<li>' + data + '</li>' +
                        '</ul>';
                    }
                    $('#success').html(html);
            }
        });
    }
</script>
