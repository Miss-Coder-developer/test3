<head>
<style>
    * {
        box-sizing: border-box;
    }

    /* Position the image container (needed to position the left and right arrows) */
    .image-container {
        position: relative;
    }

    /* Hide the images by default */
    .mySlides {
        display: none;
    }

    /* Add a pointer when hovering over the thumbnail images */
    .cursor {
        cursor: pointer;
    }

    /* Next & previous buttons */
    .prev,
    .next {
        cursor: pointer;
        width: auto;
        padding: 16px;
        color: black;
        font-weight: bold;
        font-size: 20px;
        border-radius: 0 3px 3px 0;
        user-select: none;
        -webkit-user-select: none;
    }

    /* Position the "next button" to the right */
    .next {
        right: 0;
        border-radius: 3px 0 0 3px;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev:hover,
    .next:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }

    /* Number text (1/3 etc) */
    .numbertext {
        color: #f2f2f2;
        font-size: 12px;
        padding: 8px 12px;
        position: absolute;
        top: 0;
    }

    /* Container for image text */
    .caption-container {
        text-align: center;
        background-color: #222;
        padding: 2px 16px;
        color: white;
        width: 50%;
    }

    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    /* Six columns side by side */
    .column {
        float: left;
        width: 16.66%;
    }

    /* Add a transparency effect for thumnbail images */
    .demo {
        opacity: 0.6;
    }

    .active,
    .demo:hover {
        opacity: 1;
    }


    #totalcount2, #totalcount
    {
        display:inline;
    }

</style>
</head>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Images') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="caption-container">
                    <p id="caption"></p>
                </div>

                <div>
                    <p id="scaffoldComponent"></p>
                </div>

                <div class="image-container">
@foreach($imageList as $image)
                    <!-- Full-width images with number text -->
                    <div class="mySlides" data-confirmed="{{$image['confirmed']}}" data-approved="{{$image['approved']}}"
                         data-deleted="{{$image['deleted']}}" data-url="{{$image['url']}}" data-id="{{$image['id']}}">
                        <img src="{{$image['url']}}" style="width:50%">
                        <div class="p-2" id="totals">
                            <div id="totalcount2">{{$loop->index +1 }} /</div> <div id ="totalcount">{{$size}}</div>
                        </div>
                        <div class="p-6">
                        <table class="min-w-max divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-6 whitespace-nowrap">Type</td>
                                    <td class="px-6 whitespace-nowrap">{{$image['scaffoldComponent']}}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 whitespace-nowrap">Count</td>
                                    <td class="px-6 whitespace-nowrap">{{$image['scaffoldCount']}}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 whitespace-nowrap">Date</td>
                                    <td class="px-6 whitespace-nowrap">{{$image['date']}}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 whitespace-nowrap">Status</td>
                                    <td class="px-6 whitespace-nowrap status_text" id="status">
                                    @if($image['deleted'])
                                        Deleted
                                        @elseif($image['approved'])
                                        Approved
                                        @elseif($image['confirmed'])
                                        Confirmed
                                        @else
                                        None
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 whitespace-nowrap">Version</td>
                                    <td class="px-6 whitespace-nowrap">{{$image['appVersion']}}</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <p class="alt_text" hidden>{{$image['name']}}</p>
    @endforeach
                    <!-- Next and previous buttons -->
    <div class="p-4">
                    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                    <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>
                </div>


<div class="p-4">
    <button id="confirmButton" class="confirmbutton bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
        Confirm (C)
    </button>
    <button id="approveButton" class="bg-transparent hover:bg-green-900 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
        Approve (A)
    </button>
    <button id="deleteButton" class="bg-transparent hover:bg-red-900 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded">
        Delete (D)
    </button>

</div>



            </div>
        </div>
    </div>
</x-app-layout>

<script>
    var slideIndex = 1;
    showSlides(slideIndex);

    // Next/previous controls
    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    // Thumbnail image controls
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function confirmImage(){
        // alert('imageConfirmed')
        var slides = document.getElementsByClassName("mySlides");
        slide = slides[slideIndex-1];
        id = slide.dataset.id;
        //alert(id);

        $.ajax({
            url: "{{ route('images.confirm') }}",
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
            },
            success: function(response) {
                // log response into console
                console.log(response);
                slide.dataset.confirmed = 'true';
                plusSlides(1);
            }
        });
    }

    function approveImage(){
        var slides = document.getElementsByClassName("mySlides");
        slide = slides[slideIndex-1];
        id = slide.dataset.id;
        //alert(id);

        $.ajax({
            url: "{{ route('images.approve') }}",
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
            },
            success: function(response) {
                // log response into console
                slide.dataset.approved = 'true';


                console.log(response);
                plusSlides(1);
            }
        });
    }

    function deleteImage(){
        var slides = document.getElementsByClassName("mySlides");
        slide = slides[slideIndex-1];
        id = slide.dataset.id;
        //alert(id);

        $.ajax({
            url: "{{ route('images.delete') }}",
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
            },
            success: function(response) {
                // log response into console
                slide.dataset.deleted = 'true';
                var totalcount = document.getElementById("totalcount");
                totalcount.innerText  = totalcount.innerText-1
                console.log(response);
                plusSlides(1);
            }
        });
    }

    $('#approveButton').click(function(){
        approveImage();
    })

    $('#confirmButton').click(function(){
        confirmImage();
    })

    $('#deleteButton').click(function(){
        deleteImage();
    })


    document.addEventListener('keydown', (event) => {
        var name = event.key;
        var code = event.code;
        // Alert the key name and key code on keydown
        switch (event.code){
            case "ArrowRight":
                plusSlides(1);
                break;
            case "ArrowLeft":
                plusSlides(-1);
                break;
            case "KeyC":
                confirmImage();
                break;
            case "KeyA":
                approveImage();
                break;
            case "KeyD":
                deleteImage();
                break;
            default:
                break;
        }

    }, false);

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("alt_text");
        var statuses = document.getElementsByClassName("status_text")
        var captionText = document.getElementById("caption");
        var currentNumber = document.getElementById("number");
        var confirmButton = document.getElementById('confirmButton');
        var approveButton = document.getElementById("approveButton");
        var deleteButton = document.getElementById("deleteButton")
        var statusplaceholder = document.getElementById("status");
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " active";
        captionText.innerHTML = dots[slideIndex-1].innerText;

        if(statuses[slideIndex-1].innerText=="None"){
            confirmButton.hidden = false;
            approveButton.hidden = true;
            deleteButton.hidden = false;
        }else{
            if(statuses[slideIndex-1].innerText=="Confirmed"){
                confirmButton.hidden = true;
                approveButton.hidden = false;
                deleteButton.hidden = false;
            }else{
                if(statuses[slideIndex-1].innerText=="Approved") {
                    confirmButton.hidden = true;
                    approveButton.hidden = true;
                    deleteButton.hidden = false;
                }
                else{
                    if(statuses[slideIndex-1].innerText=="Deleted") {
                        confirmButton.hidden = true;
                        approveButton.hidden = true;
                        deleteButton.hidden = true;
                    }
                }
            }
        }


    }
</script>
