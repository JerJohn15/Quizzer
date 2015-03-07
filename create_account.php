<?php
include 'conf/db.php';
include 'header.php';
?>

    <form id="createaccount" action="insert_account.php" method="post">
        <fieldset>
            <legend>Fortune Account Creation</legend>
            <label for="email">
                Email <input type="email" name="email" id="email" autofocus/>
                <span id="email-result"></span>
            </label>

            <p/>
            <label for="fname">
                First Name <input type="fname" name="fname" id="fname" minlength="2" required />
                <span id="fname-result"></span>
            </label>

            <p/>
            <label for="lname">
                Last Name <input type="lname" name="lname" id="lname" minlength="2" required />
                <span id="lname-result"></span>
            </label>

            <p/>
            <label for="passwd">
                Password <input type="password" name="passwd" id="passwd" required />
                <span id="passwd-result"></span>
            </label>

            <p/>

            <button class="fortune_button" type="submit" name="login_button">CREATE</button>
        </fieldset>
    </form>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script type="text/javascript">
        var emailOK = 0;
        $(document).ready(function () {
            $("#email").keyup(function (e) {
                var email = $(this).val();
                $("#email-result").html('<img src="img/ajax-loader.gif" />');
                $.post('check_account.php', {'email': email}, function (data) {
                    if (data == 'OK') {
                        $("#email-result").html('<img src="img/available.png" />');
                        emailOK = 1;
                    }
                    else if (data == 'NOT_OK') {
                        $("#email-result").html('<img src="img/not-available.png" />');
                        emailOK = 0;
                    }
                });
            });

            jQuery.validator.addMethod("emailValid", function(value,element,params) {
                return emailOK }, "Username is in use!" );

            $('#createaccount').validate({
                rules: {
                    email: {
                        required: true,
                        emailValid: true
                    }
                }
            });
        });
    </script>

<?php include 'footer.php'; ?>