<?php
$url_path = Request::path();
$active_profile = $active_school = $active_admin ='';
if (Request::path() == 'account')
    $active_profile = 'active';
else {
    list($first, $rest) = explode('/', Request::path(), 2);
    if ($rest) {
        $active_school = (preg_match('/^schools|grades*/', $rest)) ? 'active' : '';
        $active_admin = (preg_match('/^admin*/', $rest)) ? 'active' : '';
    }
}
?>
{{-- Mobile Sidebar Menu --}}
<div class="alert alert-light alert-elevate" role="alert" id="left-sidebar-mobile">
    <div class="alert-text">
        <select class="form-control kt-selectpicker" name="people_tab" id="people_tab" onchange="redirectEvent(this.value)">
            <option value="0" {{ ($active_profile) ? 'selected' : '' }}>Profile</option>
            <option value="1" {{ ($active_school) ? 'selected' : '' }}>Schools</option>
            <option value="2" {{ ($active_admin) ? 'selected' : '' }}>Admins</option>
        </select>
    </div>
</div>
<script>
    function redirectEvent(val) {
        switch (val) {
            case '0':
                window.location.href = "/account";
                break;
            case '1':
                window.location.href = "/account/schools";
                break;
            case '2':
                window.location.href = "/account/admins";
                break;
        }
    };
</script>