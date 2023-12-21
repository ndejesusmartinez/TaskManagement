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

        <form action="{{ route('registerProject') }}" method="post">
            @csrf
            <label for="">Crear proyecto nuevo</label>
            <div class="mb-3">
                <input class="form-control" type="text" name="title" id="title" placeholder="Nombre">
            </div>
            <div class="mb-3">
                <input class="form-control" type="text" name="description" id="description" placeholder="description">
            </div>
            <div class="mb-3">
                <input class="form-control" type="date" name="startDate" id="startDate" placeholder="Fecha de inicio">
            </div>
            <button class="btn btn-primary" type="submit">Registrar proyecto</button>
        </form>
    </div>

<br>
<br>
<br>
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id proyecto</th>
                    <th scope="col">Nombre de proyecto</th>
                    <th scope="col">Fecha de inicio proyecto</th>
                    <th scope="col">Descripcion del proyecto</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $dat)
                    <tr>
                        <td> {{ $dat->id }} </td>
                        <td> {{ $dat->title }} </td>
                        <td> {{ $dat->startDate }} </td>
                        <td> {{ $dat->description }} </td>
                        <td> <button onclick="detailProject(' {{$dat->id}} ', '{{$dat->title}}', '{{$dat->startDate}}', '{{$dat->description}}');">Editar proyecto</button> <button onclick="deleteProject(' {{$dat->id}} ');">Eliminar proyecto</button> <button onclick="seeTaks(' {{$dat->id}} ');" >Ver tareas del proyecto</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tareas del proyecto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <table>
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Nombre de tarea</td>
                    </tr>
                </thead>
                <tbody class ="bodyTable">
                    <tr>

                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>

    </div>

    <div class="modal fade" id="detailProject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar proyecto # <label id="projectNumber"></label></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('updateProject') }}" method="post">
                @csrf
                <div class="mb-3">
                    <input type="text" class="form-control" name="id" id="idp" value="" readonly>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="title" id="titlep" value="">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="description" id="descriptionp" placeholder="description">
                </div>
                <div class="mb-3">
                    <input type="date" class="form-control" name="startDate" id="startDatep" placeholder="Fecha de inicio">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>

        </div>
        </div>
    </div>

    </div>

        <button type="button" id ="prueba"class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="display:none;">
            Launch demo modal
        </button>

        <button type="button" id ="prueba2"class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailProject" style="display:none;">
            Launch demo modal2
        </button>
@endsection
</body>
</html>

<script>
    function deleteProject(id) {
        formData = new FormData();
        formData.append('id',id);
        token = validartoken();
        formData.append('_token',token);

        $.ajax({
            type:"POST",
            url:   "{{ route('deleteProject') }}",
            data : formData,
            processData : false,
            contentType : false,
            success : function(result, status, xhr){
                if(result.code == 200){
                    location.reload();
                }
            }
        });
    }

    function validartoken() {
        token = $('#token').val();
        return token;
    }

    function seeTaks(id){
        formData = new FormData();
        formData.append('id',id);
        token = validartoken();
        formData.append('_token',token);

        $.ajax({
            type:"POST",
            url:   "{{ route('taskForProject') }}",
            data : formData,
            processData : false,
            contentType : false,
            success : function(result, status, xhr){
                if(result.code == 200){
                    //console.log(result.data)
                    let button = document.getElementById('prueba');
                    button.click();
                }else{
                    alert("error")
                }

            }
        });
    }

    function detailProject(id, title, starDate, description){

        let projectNumber = document.getElementById('projectNumber');
        projectNumber.innerHTML = id;

        let idp = document.getElementById('idp');
        idp.value = id;

        let titlep = document.getElementById('titlep');
        titlep.value = title;

        let startDatep = document.getElementById('startDatep');
        startDatep.value = starDate;

        let descriptionp = document.getElementById('descriptionp');
        descriptionp.value = description;

        let detail = document.getElementById('prueba2');
        detail.click();


    }
</script>
