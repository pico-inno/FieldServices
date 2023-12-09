



// Super Wheel Script
jQuery(document).ready(function($){




	$('.wheel-standard').superWheel({
		slices: [
		{
				text: "20% OFF",
				value: 1,
				message: "You win 20% off",
				discount: "95Qm9tof",
				background: "#364C62",

			},
			{
				text: "No luck",
				value: 0,
				message: "You have No luck today",
				discount: "no",
				background: "#9575CD",

			},
			{
				text: "30% OFF",
				value: 1,
				message: "You win 30% off",
				discount: "8C46fBeH",
				background: "#E67E22",

			},
			{
				text: "Lose",
				value: 0,
				message: "You Lose :(",
				discount: "no",
				background: "#E74C3C",

			},
			{
				text: "40% OFF",
				value: 1,
				message: "You win 40% off",
				discount: "aHiH4bfd",
				background: "#2196F3",

			},
			{
				text: "Nothing",
				value: 0,
				message: "You get Nothing :(",
				discount: "no",
				background: "#95A5A6",

			}
	],
	text : {
		size: 14,
        color: '#fff',
        offset : 8,
        letterSpacing: 0,
        orientation: 'v',
        arc: true
	},
	line: {

			color: "#ecf0f1"
		},
		outer: {
			color: "#ecf0f1"
		},
		inner: {
			color: "#ecf0f1"
		},
		center: {
			rotate: 1,
		},
		marker: {
			background: "#e53935",
			animate: 1
		},

	selector: "value",



	});



	var tick = new Audio('/assets/spinwheel/media/tick.mp3');

	$(document).on('click','.wheel-standard-spin-button',function(e){

		$('.wheel-standard').superWheel('start',1);
		$(this).prop('disabled',true);
	});



	$('.wheel-standard').superWheel('onStart',function(results){


		$('.wheel-standard-spin-button').text('Spinning...');

	});
	$('.wheel-standard').superWheel('onStep',function(results){

		if (typeof tick.currentTime !== 'undefined')
			tick.currentTime = 0;

		tick.play();

	});


	$('.wheel-standard').superWheel('onComplete',function(results){
		//console.log(results.value);
		if(results.value === 1){

            Swal.fire({
                title: "Congratulations",
                icon: "success",
                html: results.message+' <br><br><b>Discount : [ '+ results.discount+ ' ]</b>',
                // showCancelButton: true,
                // buttonsStyling: false,
                // confirmButtonText: "Yes, Checkout!",
                // cancelButtonText: "No, cancel",
                // customClass: {
                //     confirmButton: "btn btn-sm fw-bold btn-danger",
                //     cancelButton: "btn btn-sm fw-bold btn-active-light-primary"
                //     }
            })

		}else{
			swal("Oops!", results.message, "error");
		}


		$('.wheel-standard-spin-button:disabled').prop('disabled',false).text('Spin');

	});





});
