@extends('layout')

@section('content')
    <h2 class="text-center">Add a new author together with a new book</h2>

    <form action="{{ route('authors.store') }}" method="POST" name="new_author"
          onsubmit="return futureReleaseDateConfirmation(this);">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name" class="required">Author Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                           placeholder="Enter Author Name" maxlength="50" required>
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="birth_date" class="required">Author Birth Date</label>
                    <input type="date" name="birth_date" id="birth_date" class="form-control"
                           placeholder="Enter Author Birth Date" onblur="setReleaseDateMin(this.value);" required>
                    <span class="text-danger">{{ $errors->first('birth_date') }}</span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="address">Author Address</label>
                    <input type="text" name="address" id="address" class="form-control"
                           placeholder="Enter Author Address" maxlength="255">
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="title" class="required">Book Title</label>
                    <input type="text" name="title" id="title" class="form-control"
                           placeholder="Enter Book Title" maxlength="100" required>
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="release_date" class="required">Book Release Date</label>
                    <input type="date" name="release_date" id="release_date" class="form-control"
                           aria-describedby="releaseDateHelpBlock" placeholder="Enter Book Release Date" required>
                    <small id="releaseDateHelpBlock" class="form-text">
                        The release date must be at least 5 years after the author's birth date
                    </small>
                    <span class="text-danger">{{ $errors->first('release_date') }}</span>
                </div>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-success">Submit</button>
                <a href="{{ route('authors.index') }}" class="btn btn-warning">Back to List</a>
            </div>
        </div>

    </form>

    <script>
        window.onload = setAuthorBirthDateMaxNow;

        function setAuthorBirthDateMaxNow() {
            let today = dateFormatter(new Date());
            document.getElementById("birth_date").setAttribute("max", today);
        }

        function setReleaseDateMin(birthDateValue) {
            let fiveYearsFromBirthDate = new Date(birthDateValue);
            fiveYearsFromBirthDate.setFullYear(fiveYearsFromBirthDate.getFullYear() + 5);

            let fiveYearsFromBirthDateFormatted = dateFormatter(fiveYearsFromBirthDate);
            document.getElementById("release_date").setAttribute("min", fiveYearsFromBirthDateFormatted);
        }

        function dateFormatter(date) {
            let dd = date.getDate();
            let mm = date.getMonth() + 1; //January is 0!
            let yyyy = date.getFullYear();
            if (dd < 10) {
                dd = '0' + dd
            }
            if (mm < 10) {
                mm = '0' + mm
            }

            return yyyy + '-' + mm + '-' + dd;
        }

        function futureReleaseDateConfirmation() {
            let tenYearsFromNow = new Date();
            tenYearsFromNow.setFullYear(tenYearsFromNow.getFullYear() + 10);

            let releaseDate = new Date(document.getElementById('release_date').value);

            if (releaseDate > tenYearsFromNow) {
                return confirm('The entered release date is more than 10 years from now. Are you sure?');
            }
        }
    </script>
@endsection
