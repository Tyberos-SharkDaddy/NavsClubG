<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* General Styling */
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f4;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh;
            margin: 0;
        }
        .checkout-container { 
            width: 100%; 
            max-width: 400px; 
            background: #fff; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        h2, h3 { text-align: center; color: #333; }
        
        /* Form Elements */
        label { font-weight: bold; display: block; margin-top: 10px; color: #555; }
        select, input { 
            width: 100%; 
            padding: 10px; 
            margin-top: 5px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            font-size: 16px;
        }
        .payment-options { 
            display: flex; 
            justify-content: center; 
            gap: 10px; 
            margin-top: 10px;
        }
        .payment-options input { display: none; }
        .payment-options label { 
            padding: 10px 15px; 
            border: 2px solid #ddd; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: all 0.3s;
        }
        .payment-options input:checked + label { 
            background: #007BFF; 
            color: white; 
            border-color: #007BFF;
        }
        
        /* Card Details */
        #card-details { 
            display: block; 
            transition: 0.3s ease-in-out;
        }
        
        /* Pay Now Button */
        .pay-btn { 
            width: 100%; 
            background: linear-gradient(135deg, #007BFF, #0056b3); 
            color: white; 
            padding: 12px; 
            border: none; 
            border-radius: 5px; 
            font-size: 18px; 
            margin-top: 15px; 
            cursor: pointer; 
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
        }
        .pay-btn:hover { 
            background: linear-gradient(135deg, #0056b3, #004494);
        }
        
        /* Hide Elements */
        .hidden { display: none; }
    </style>
</head>
<body>

<div class="checkout-container">
    <h2>Checkout</h2>
    
    <label for="billing_country">Billing Country:</label>
    <select id="billing_country">
        <option value="Philippines">Philippines</option>
        <option value="USA">USA</option>
    </select>

    <label for="course_name">Course Name:</label>
    <input type="text" id="CourseName" placeholder="Enter course name" readonly>

    <h3>Payment Method</h3>
    <div class="payment-options">
        <input type="radio" id="pay-card" name="payment_method" value="card" checked>
        <label for="pay-card">Card</label>

        <input type="radio" id="pay-paypal" name="payment_method" value="paypal">
        <label for="pay-paypal">PayPal</label>

        <input type="radio" id="pay-grabpay" name="payment_method" value="grabpay">
        <label for="pay-grabpay">GrabPay</label>
    </div>

    <div id="card-details">
        <label>Card Number:</label>
        <input type="text" id="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
        
        <label>Expiry Date:</label>
        <input type="text" id="expiry_date" placeholder="MM/YY" maxlength="5">
        
        <label>CVV:</label>
        <input type="text" id="cvv" placeholder="123" maxlength="3">
    </div>

    <button class="pay-btn" id="payNow">Pay Now</button>
</div>

<script>
$(document).ready(function(){
    $('input[name="payment_method"]').change(function(){
        if ($(this).val() === "card") {
            $("#card-details").slideDown();
        } else {
            $("#card-details").slideUp();
        }
    });

    $("#card_number").on("input", function(){
        let val = $(this).val().replace(/\D/g, "");
        val = val.replace(/(.{4})/g, "$1 ").trim();
        $(this).val(val);
    });

    $("#expiry_date").on("input", function(){
        let val = $(this).val().replace(/\D/g, "");
        if (val.length > 2) {
            val = val.slice(0,2) + "/" + val.slice(2,4);
        }
        $(this).val(val);
    });

    $("#cvv").on("keypress", function(e){
        if (e.which < 48 || e.which > 57) {
            return false;
        }
    });

    $("#payNow").click(function(){
        var payment_method = $('input[name="payment_method"]:checked').val();
        var billing_country = $("#billing_country").val();
        var course_name = $("#course_name").val().trim();
        var card_number = $("#card_number").val();
        var expiry_date = $("#expiry_date").val();
        var cvv = $("#cvv").val();

        if (course_name === "") {
            alert("Please enter the Course Name.");
            return;
        }

        if (payment_method === "card" && (card_number.length < 19 || expiry_date.length < 5 || cvv.length < 3)) {
            alert("Please enter a valid card number, expiry date, and CVV.");
            return;
        }

        var data = {
            billing_country: billing_country,
            course_name: course_name,
            payment_method: payment_method,
        };

        if (payment_method === "card") {
            data.card_number = card_number;
            data.expiry_date = expiry_date;
            data.cvv = cvv;
        }

        $.ajax({
            url: "checkoutprocess.php",
            type: "POST",
            data: data,
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    alert("Payment successful!");
                    window.location.href = "confirmation_page.php"; // Redirect if needed
                } else {
                    alert("Payment failed: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", xhr.responseText);
                alert("Error processing payment! Please try again.");
            }
        });
    });
});
</script>

</body>
</html>
