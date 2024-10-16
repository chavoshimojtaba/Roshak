<?php

$grid = [
	'container_class' => 'content-grid',
	'container_element' => 'tbody',
	'api' => 'member/ticket',
	'template' => '
	 <tr>
		<th scope="row">[key]</th>
		<td>[title]</td>
		<td>[role]</td>
		<td>[last_update]</td>
		<td>
			<[a] class="bg-[color] d-block bg-opacity-25 text-center p-2 rounded-3" href="[HOST]dashboard/ticket/[id]">
				<span class="text-[color]">
					[status]
				</span>
			</[a]>
		</td>
 	</tr>'
];
$grid_mobile = [
	'container_class' => 'content-grid accordion position-relative',
	'container_attr' => 'id="accordionExample"',
	'api' => 'member/ticket',
	'template' => '
	 <div class="accordion-item">
		<div class="accordion-header py-2" id="heading[key]">
				<button
					class="accordion-button px-1 collapsed flex-column align-items-baseline py-1 text-dark fs-5"
					type="button" data-bs-toggle="collapse" data-bs-target="#collapse[key]"
					aria-expanded="true" aria-controls="collapse[key]">
				<div class="flex-fill flex-grow-1">
					<div class="fs-6 text-secondary">
						موضوع:
					</div>
					<div class="fs-6 text-dark">
						[title]
					</div>
				</div>
			</button>
		</div>
		<div id="collapse[key]" class="accordion-collapse collapse  "
			aria-labelledby="heading[key]" data-bs-parent="#accordionExample">
			<div class="accordion-body text-muted py-0">
				<div class="d-flex flex-column">
					<div class="td-label py-2">
						واحد مربوطه:
						<span>[role]</span>
					</div>
					<div class="td-value flex-fill py-2">
						تاریخ و ساعت:
						<span>[last_update]</span>
					</div>
					<[a] class="td-value flex-fill py-2 mb-2"   href="[HOST]dashboard/ticket/[id]">
						وضعیت:
						<span
							class="bg-[color] bg-opacity-25 text-[color] p-1 px-2 fs-7 rounded-3">
							[status]
						</span>
					</[a]>
				</div>
			</div>
		</div>
	</div> '
];
