<html>
    <title>Dependency Dropdowns</title>
    <head>
            <meta name="csrf-token" content="{{ csrf_token() }}" />
        </head>
</html>
<form>
    <select id="country" name="country" onchange="get_state(this.value,'country')">
    <option value="" selected>Select Country</option>
    @if(!empty($country))
    @foreach($country as $val)
    <option value="{{ $val->id}}">{{$val->name}}</option>
    @endforeach
    @endif
    </select><br>
    <select id="state" name="state" class="country" onchange="get_state(this.value,'state')">
    <option value="" selected>Select State</option>
    </select><br>
    <select id="city" name="city" class="state">
    <option value="" selected>Select City</option>
    </select>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    
    function get_state(id,val)
    {
        var base_url = window.location.origin + "/";
        if(val == 'country')
        {
            main_url = 'get_state';
        }
        if(val == 'state')
        {
            main_url = 'get_city';
        }
        
        $.ajax({
                url: base_url+main_url,
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {id:id},
                success: function(result) {
                    $('.'+val).html(result.output);
                }

            })
    }
</script>