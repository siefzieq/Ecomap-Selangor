@extends('layouts.app')
<style>
    form {
        padding-top: 50px;
        padding-left: 40px;
    }

    input, textarea, select {
        background-color: #F7F7F7 !important;
    }

    .btn.btn-outline {
        border-color: #2D6C2B;
        color: #2D6C2B;
    }

    .btn.btn-outline:hover {
        background-color: #2D6C2B;
        color: #ffffff;
    }

    .suggestions-container {
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 4px;
        position: absolute;
        z-index: 999;
        max-height: 200px;
        overflow-y: auto;
        display: none; /* Hide initially */
    }

    .suggestion-item {
        padding: 10px;
        cursor: pointer;
    }

    .suggestion-item:hover {
        background-color: #f0f0f0;
    }
</style>

@section('title', 'Add Flat')
@section('caption', 'Add Flat Information')
@section('content')
    <div style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); position: relative; margin-top: 3%; left: -0.5%; width: 96%; height: 70%; overflow: hidden;">
        <div style="height: calc(100% - 20px); overflow-y: auto;">
            <form method="POST" action="{{route('flat.store')}}" enctype="multipart/form-data" style="width: 100%;">
                @csrf
                <label class="form-label">Flat Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter the flat name" style="width: 95%;" autocomplete="off" required>
                <br>

                <label class="form-label">Address</label>
                <div class="input-group mb-3" style="width: 95%;">
                    <span class="input-group-text" id="inputGroup-sizing-default" style="background-color: #FFFFFF"><i class="fi fi-rr-marker"></i></span>
                    <input type="text" class="form-control" id="place-name" name="address" placeholder="Enter the address" style="width: 95%;" autocomplete="off">
                </div>
                <div id="suggestions-container" class="suggestions-container" style="width: 92%;"></div>
                <br>

                <label class="form-label">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Enter the longitude" style="width: 95%;" autocomplete="off" required><br>

                <label class="form-label">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Enter the latitude" style="width: 95%;" autocomplete="off" required><br>

                <label class="form-label">City</label>
                <div class="input-group mb-3" style="width: 95%;">
                    <input type="text" class="form-control" id="cityValue" placeholder="Enter the city" style="width: 95%;" disabled>
                    <input type="hidden" id="city" name="city" placeholder="Enter the city" style="width: 95%;"><br>
                </div>

                <div class="d-flex justify-content-end gap-2" style="padding-top: 30px; padding-right: 60px">
                    <a href="{{route('plant.index')}}" role="button" type="reset" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn text-white" style="background-color:#2D6C2B; border-radius: 8px; height: 38px; width: 80px;">Add</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoiYXNlcDEzMzAyIiwiYSI6ImNseG8zcW43NDA5MmYya3M5MnlmaXQxeGEifQ.IS2UP8qcdRDJfhm2oTOiiQ';

        const placeNameInput = document.getElementById('place-name');

        function fetchPlaceSuggestions(query) {
            // Adding proximity bias for Selangor (using Shah Alam's approximate center)
            const proximity = '101.5334,3.0738'; // Shah Alam coordinates
            // Narrow down search within the Selangor region
            const bbox = '100.914,-3.092,101.975,3.263'; // Approximate bounding box for Selangor
            const encodedQuery = encodeURIComponent(query);
            const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodedQuery}.json?country=MY&bbox=${bbox}&access_token=${mapboxgl.accessToken}&autocomplete=true`;

            console.log('Fetching URL:', url);

            return fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    return data.features; // assuming all features are relevant
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        }


        function displayPlaceSuggestions(suggestions) {
            const suggestionsContainer = document.getElementById('suggestions-container');
            suggestionsContainer.innerHTML = '';
            if (suggestions.length > 0) {
                suggestionsContainer.style.display = 'block';
                suggestions.forEach(function(suggestion) {
                    const suggestionElement = document.createElement('div');
                    suggestionElement.textContent = suggestion.place_name;
                    suggestionElement.classList.add('suggestion-item');
                    suggestionElement.addEventListener('click', function() {
                        selectPlaceSuggestion(suggestion);
                    });
                    suggestionsContainer.appendChild(suggestionElement);
                });
            } else {
                suggestionsContainer.style.display = 'block';  // Ensure container is visible
                const noResultsElement = document.createElement('div');  // Create a no results item
                noResultsElement.textContent = 'No results';
                noResultsElement.classList.add('suggestion-item');
                suggestionsContainer.appendChild(noResultsElement);  // Append the no results message
            }
        }

        function selectPlaceSuggestion(suggestion) {
            console.log('Selected Suggestion:', suggestion);
            document.getElementById('place-name').value = suggestion.place_name;
            document.getElementById('longitude').value = suggestion.center[0];
            document.getElementById('latitude').value = suggestion.center[1];
            const cityContext = suggestion.context.find(ctx => ctx.id.startsWith('place'));
            if (cityContext) {
                document.getElementById('city').value = cityContext.text;
                document.getElementById('cityValue').value = cityContext.text;
            }
            document.getElementById('suggestions-container').style.display = 'none';
        }

        function debounce(func, delay) {
            let debounceTimer;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(context, args), delay);
            };
        }

        placeNameInput.addEventListener('input', debounce(function() {
            const query = this.value;
            console.log('Input event triggered, query:', query);
            if (query.length > 2) {
                fetchPlaceSuggestions(query).then(suggestions => {
                    console.log('Suggestions received:', suggestions);
                    displayPlaceSuggestions(suggestions);
                });
            } else {
                document.getElementById('suggestions-container').innerHTML = '';
                document.getElementById('suggestions-container').style.display = 'none';
            }
        }, 300));

        function GetCity() {
            const lon = document.getElementById('longitude').value;
            const lat = document.getElementById('latitude').value;

            // Ensure both longitude and latitude are filled and are valid numbers
            if(lon && lat && !isNaN(lon) && !isNaN(lat)) {
                getCityFromCoordinates(lon, lat);
            }
        }

        function getCityFromCoordinates(lon, lat) {
            var url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${lon},${lat}.json?access_token=${mapboxAccessToken}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Log the response to check the data
                    let city = '';
                    // Iterate over features to find place and address
                    data.features.forEach(feature => {
                        if (feature.place_type.includes('place')) {
                            city = feature.text; // this is usually the city name
                        }
                    });
                    updateText(city);
                })
                .catch(error => console.error('Error:', error));
        }

        function updateText(city) {
            const cityInput = document.getElementById('city');
            const cityValueInput = document.getElementById('cityValue');
            cityInput.value = city;
            cityValueInput.value = city;
        }
    </script>

@endsection
