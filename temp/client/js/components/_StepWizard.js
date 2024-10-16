import { scrollTop } from '../util/util';
function StepWizard(options) { 
	const _this = this; 
	var steps = 1;
	var currentStep = 1; 
	let containerEl; 
	let defaultOptions = {  
	};

	options = { ...defaultOptions, ...options };

	this.render = function () {
		containerEl = document.querySelector(`.${options.cls}`); 
		steps = options.steps;
		_this.stepFormWizard()
	};
 
	this.updateProgressBar = function (stepNumber) {  
		var progressPercentage = ((currentStep - 1) / (steps - 1)) * 100;
		$(".progress-bar").css("width", progressPercentage + "%");
	}
	this.displayStep = function (stepNumber) {  
		if (stepNumber >= 1 && stepNumber <= steps) {
			$(".step-" + currentStep).hide();
			$(".step-" + stepNumber).show();
			currentStep = stepNumber;
			_this.updateProgressBar(); 
			// if (stepNumber == 1 && options.onInitilized != null) {
			if (  options.onInitilized != null) { 
				options.onInitilized();
			}
		}
	}
	this.stepFormWizard = function () {  
		document.querySelectorAll('.step-circle')[0].classList.add('current');
		$(".next-step").click(function () {  
			if (!options.onNext(currentStep)) {
				return;
			} 
			if (currentStep < steps) {
				$(".step").removeClass("d-block");
				document.querySelectorAll('.step-circle')[currentStep - 1].classList.add('active');
				document.querySelectorAll('.step-circle')[currentStep].classList.add('current');
				$(".step-" + currentStep).addClass("animate__animated animate__fadeOutLeft");
				currentStep++;
				$(".step").removeClass("animate__animated animate__fadeOutLeft").hide();
				$(".step-" + currentStep).addClass("animate__animated animate__fadeInRight d-block");
				_this.updateProgressBar();
				scrollTop();
			}
			if ((currentStep) == steps) {
				document.querySelectorAll('.step-circle')[steps - 1].classList.add('active');
			}
		});
		$(".prev-step").click(function () {
			$(".step").removeClass("d-block");
			if (currentStep > 1) { 
				$(".step-circle").removeClass("current"); 
				document.querySelectorAll('.step-circle')[currentStep - 1].classList.remove('active');
				document.querySelectorAll('.step-circle')[currentStep - 2].classList.remove('active');
				document.querySelectorAll('.step-circle')[currentStep - 2].classList.add('current');
				$(".step-" + currentStep).addClass("animate__animated animate__fadeOutRight");
				currentStep--;
				$(".step").removeClass("animate__animated animate__fadeOutRight").hide();
				$(".step-" + currentStep).show().addClass("animate__animated animate__fadeInLeft");
				_this.updateProgressBar();
				scrollTop();
			}
		}); 
		_this.displayStep(1);
	};
	this.render();
}

export { StepWizard };
