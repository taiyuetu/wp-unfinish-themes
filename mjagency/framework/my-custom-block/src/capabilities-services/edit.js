import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const { label, titleTop, titleEm, intro, services = [] } = attributes;

	const updateService = (index, key, value) => {
		const newServices = services.map((service, i) => {
			if (i === index) {
				return { ...service, [key]: value };
			}
			return service;
		});
		setAttributes({ services: newServices });
	};

	return (
		<section {...useBlockProps({ id: 'services' })}>
			<div className="services-header">
				<div>
					<RichText
						tagName="div"
						className="section-label"
						value={label}
						onChange={(val) => setAttributes({ label: val })}
						placeholder={__('Label...', 'capabilities-services')}
					/>
					<h2 className="services-title">
						<RichText
							tagName="span"
							value={titleTop || ''}
							onChange={(val) => setAttributes({ titleTop: val })}
							placeholder={__('Title Top...', 'capabilities-services')}
						/>
						<br />
						<RichText
							tagName="em"
							value={titleEm || ''}
							onChange={(val) => setAttributes({ titleEm: val })}
							placeholder={__('Emphasized...', 'capabilities-services')}
						/>
					</h2>
				</div>
				<RichText
					tagName="p"
					className="services-intro"
					value={intro}
					onChange={(val) => setAttributes({ intro: val })}
					placeholder={__('Intro text...', 'capabilities-services')}
				/>
			</div>

			<div className="services-list">
				{services.map((service, index) => (
					<div key={index} className={`service-item reveal reveal-delay-${index}`}>
						<RichText
							tagName="div"
							className="service-num"
							value={service.num}
							onChange={(val) => updateService(index, 'num', val)}
						/>
						<RichText
							tagName="div"
							className="service-name"
							value={service.name}
							multiline="br"
							onChange={(val) => updateService(index, 'name', val)}
						/>
						<RichText
							tagName="div"
							className="service-desc"
							value={service.desc}
							onChange={(val) => updateService(index, 'desc', val)}
						/>
						<div className="service-arrow">↗</div>
					</div>
				))}
			</div>
		</section>
	);
}