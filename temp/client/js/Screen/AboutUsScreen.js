import { owlCarousel } from "../util/util";

export const AboutUsScreen = {
	render() {
		owlCarousel('.card-about-slider', {
			items: 2,
			dots: !1,
			responsive: {
				0: { items: 1 },
				425: { items: 2 },
				1200: { items: 3 },
			},
			navText: [
				"<i class='d-block icon fs-4 icon-arrow-right-14'></i>",
				"<i class='d-block icon fs-4 icon-arrow-left4'></i>",
			],
		});
	}
};