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
                        <td> <button onclick="editTask(' {{$dat->id}} ', '{{$dat->name}}', '{{$dat->description}}', '{{$dat->dateEnd}}', '{{$dat->status}}', '{{$dat->userId}}', '{{$dat->project}}');" >Editar</button> <button>Eliminar</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
</script>
