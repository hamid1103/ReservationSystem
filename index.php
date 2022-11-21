<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Science Planner</title>
    <link href="css/bootstrap.css" rel="stylesheet">

</head>
<body>
<section class="d-flex flex-row mb-3 align-content-center justify-content-center">
    <!--Section left of form-->
    <div class="container-lg w-25 border border-dark p-3 mt-3">
    </div>

    <!--Form in the middle-->
<div class="container-lg w-25 border border-dark p-3 mt-3">
    <form>
        <div class="mb-3">
            <label for="InputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="InputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="m-3">
            <label for="InputDate" class="form-label">Datum</label>
            <input type="datetime-local" class="form-control">
        </div>
        <div class="mb-3">
            <label for="Subject" class="form-label">Subject</label>
            <input type="text" class="form-control" id="Subject">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

    <!--section right of form-->


</section>



<script src="js/bootstrap.bundle.js"></script>
</body>
</html>
