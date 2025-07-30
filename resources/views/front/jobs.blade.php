@extends('front.layouts.app')

@section('main')
    <section class="section-3 py-5 bg-2 ">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-10 ">
                    <h2>Find Jobs</h2>
                </div>
                <div class="col-6 col-md-2">
                    <div class="align-end">
                        <select name="sort" id="sort" class="form-control">
                            <option {{ Request::get('sort') == '0' ? 'selected' : '' }} value="0">Latest</option>
                            <option {{ Request::get('sort') == '1' ? 'selected' : '' }} value="1">Oldest</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row pt-5">
                <div class="col-md-4 col-lg-3 sidebar mb-4">
                    <form action="" id="searchForm">
                        <div class="card border-0 shadow p-4">
                            <div class="mb-4">
                                <h2>Keywords</h2>
                                <input type="text" name="keyword" id="keyword" placeholder="Keywords"
                                    class="form-control" value="{{ Request::get('keyword') }}">
                            </div>

                            <div class="mb-4">
                                <h2>Location</h2>
                                <input type="text" name="location" id="location" placeholder="Location"
                                    class="form-control" value="{{ Request::get('location') }}">
                            </div>

                            <div class="mb-4">
                                <h2>Category</h2>
                                <select name="category" id="category" class="form-select">
                                    <option value="">Select a Category</option>
                                    @if ($categories->isNotEmpty())
                                        @foreach ($categories as $item)
                                            <option {{ Request::get('category') == $item->id ? 'selected' : '' }}
                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-4">
                                <h2>Job Type</h2>
                                @if ($jobTypes->isNotEmpty())
                                    @foreach ($jobTypes as $item)
                                        <div class="form-check mb-2">
                                            <input {{ in_array($item->id, $jobTypeArray) ? 'checked' : '' }}
                                                class="form-check-input" name="job_type" id="job_type-{{ $item->id }}"
                                                type="checkbox" value="{{ $item->id }}">
                                            <label class="form-check-label" for="">{{ $item->name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="mb-4">
                                <h2>Experience</h2>
                                <select name="experience" id="experience" class="form-control">
                                    <option value="">Select Experience</option>
                                    <option {{ Request::get('experience') == 1 ? 'selected' : '' }} value="1">1 year
                                    </option>
                                    <option {{ Request::get('experience') == 2 ? 'selected' : '' }} value="2">2 years
                                    </option>
                                    <option {{ Request::get('experience') == 3 ? 'selected' : '' }} value="3">3 years
                                    </option>
                                    <option {{ Request::get('experience') == 4 ? 'selected' : '' }} value="4">4 years
                                    </option>
                                    <option {{ Request::get('experience') == 5 ? 'selected' : '' }} value="5">5 years
                                    </option>
                                    <option {{ Request::get('experience') == 6 ? 'selected' : '' }} value="6">6 years
                                    </option>
                                    <option {{ Request::get('experience') == 7 ? 'selected' : '' }} value="7">7 years
                                    </option>
                                    <option {{ Request::get('experience') == 8 ? 'selected' : '' }} value="8">8 years
                                    </option>
                                    <option {{ Request::get('experience') == 9 ? 'selected' : '' }} value="9">9 years
                                    </option>
                                    <option {{ Request::get('experience') == 10 ? 'selected' : '' }} value="10">10
                                        years
                                    </option>
                                    <option {{ Request::get('experience') == '10_plus' ? 'selected' : '' }}
                                        value="10_plus">10+
                                        years</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary mb-3">Search</button>
                            <a type="reset" href="{{ route('jobs') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
                <div class="col-md-8 col-lg-9 ">
                    <div class="job_listing_area">
                        <div class="job_lists">
                            <div class="row">
                                @if ($jobs->isNotEmpty())
                                    @foreach ($jobs as $item)
                                        <div class="col-md-4 d-flex mb-4">
                                            <div class="card border-0 p-3 shadow mb-4 w-100 h-100 d-flex flex-column">
                                                <div class="card-body flex-grow-1 d-flex flex-column">
                                                    <h3 class="border-0 fs-5 pb-2 mb-0">{{ $item->title }}</h3>
                                                    <p>{{ Str::words($item->description, 5) }}</p>
                                                    <div class="bg-light p-3 border mt-auto d-flex flex-column justify-content-center"
                                                        style="min-height: 120px;">
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                            <span class="ps-1">{{ $item->location }}</span>
                                                        </p>
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                            <span class="ps-1">{{ $item->jobType->name }}</span>
                                                        </p>
                                                        @if (!is_null($item->salary))
                                                            <p class="mb-0">
                                                                <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                                <span class="ps-1">{{ $item->salary }}</span>
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="d-grid mt-3">
                                                        <a href="{{ route('jobDetail', $item->id) }}"
                                                            class="btn btn-primary btn-lg">Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="col-md-12">Jobs not Found</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{ $jobs->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#searchForm").submit(function(e) {
            e.preventDefault();

            let url = '{{ route('jobs') }}?';
            const keyword = $("#keyword").val();
            const location = $("#location").val();
            const category = $("#category").val();
            const experience = $("#experience").val();
            const sort = $("#sort").val();
            const checkedJobType = $("input:checkbox[name='job_type']:checked").map(function() {
                return $(this).val()
            }).get()


            if (keyword !== "") {
                url += "&keyword=" + keyword
            }

            if (location !== "") {
                url += "&location=" + location
            }

            if (category !== "") {
                url += "&category=" + category
            }

            if (experience !== "") {
                url += "&experience=" + experience
            }

            if (checkedJobType.length > 0) {
                url += "&jobType=" + checkedJobType
            }

            url += "&sort=" + sort

            window.location.href = url;

        })

        $("#sort").change(function() {
            $("#searchForm").submit()
        })
    </script>
@endsection
