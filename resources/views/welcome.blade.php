@extends('layouts.app')


@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Posts</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Posts</li>
    </ol>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
        </div>

        <div class="card-body">          
            <div id="breed-list" class="row">

            </div>

        </div>
    </div>
</div>
@endsection

@push('js')
<script>



var favoriteDogs = @json($favoriteDogs);

function fetchBreeds() {
    $.ajax({
        url: 'https://dog.ceo/api/breeds/list/all',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === "success") {
                const breeds = response.message;
                // Loop through each breed and fetch a random image
                for (const breed in breeds) {
                    fetchBreedImage(breed);
                }
              
            } else {
                $('#breed-list').html('<p>Failed to load breeds.</p>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching breed list:', textStatus, errorThrown);
            $('#breed-list').html(`<p>Error fetching breed list: ${textStatus}</p>`);
        }
    });
}

// Function to fetch a random image for a specific breed
function fetchBreedImage(breed) {
    

    $.ajax({
        url: `https://dog.ceo/api/breed/${breed}/images/random`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === "success") {
                // Create HTML for the breed and image
                
                
                if(favoriteDogs.includes(breed)){                  
                    var style = 'btn-primary';
                    var like = 'true';
                    var selected = "selected";
                }else{
                    var style = 'btn-secondary';
                    var like = 'false';
                    var selected = "";
                }

                var breedHtml = `
                    <div class="breed col-md-4  ${selected}" id="breedContainer${breed}">
                        <h3>${capitalize(breed)}</h3>
                        <img src="${response.message}"  width="200" height="200"><br>
                        <button id="button${breed}" rel="${like}" title="You like this Dog" data-name="${breed}" class="like btn ${style} btn-sm btn-block">Like</button>
                        <hr>
                    </div>`;
                        
            
                
                $('#breed-list').append(breedHtml);   

                $(`#button${breed}`).on('click', function () {

                        const breedName = $(this).data('name'); 
                        const liked = $(this).attr('rel');    

                        if(liked == 'true'){
                            
                            $.ajax({
                                url: '/dogs/select',
                                method: 'POST',
                                data: {
                                    dogs: breedName, liked:liked
                                },
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success: function(response) {                                 
                                    $(`#button${breedName}`).removeClass('btn-primary');
                                    $(`#button${breedName}`).addClass('btn-secondary');  
                                    $(`#breedContainer${breedName}`).removeClass('selected');   
                                                              
                                    alert(response.message);
                                },
                                error: function() {
                                    alert('Failed to save favorite dogs.');
                                }
                            });
                        }else{

                             if(favoriteDogs.length >= 3){

                                alert('Select only 3');   

                            }else{
                                $.ajax({
                                    url: '/dogs/select',
                                    method: 'POST',
                                    data: {
                                        dogs: breedName, liked:liked
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        $(`#button${breedName}`).removeClass('btn-secondary');
                                        $(`#button${breedName}`).addClass('btn-primary');  
                                        $(`#breedContainer${breedName}`).addClass('selected');   
                                        alert(response.message);
                                    },
                                    error: function() {
                                        alert('Failed to save favorite dogs.');
                                    }
                                });
                            }  
                        }
                            

                        
                    });

            } else {
                console.error(`Failed to load image for ${breed}`);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error(`Error fetching image for ${breed}:`, textStatus, errorThrown);
        }
    });
}

// Helper function to capitalize breed names
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Fetch all breeds on page load
$(document).ready(fetchBreeds);


</script>
@endpush