import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const { sectionLabel, philosophyStatement, philosophyText1, philosophyText2, stats } = attributes;

	const updateStat = (index, key, value) => {
		const newStats = stats.map((stat, i) =>
			i === index ? { ...stat, [key]: value } : stat
		);
		setAttributes({ stats: newStats });
	};

	return (
		<section {...useBlockProps({ id: 'philosophy', className: 'wp-block-philosophy-section' })}>
			<div className="phil-left">
				<RichText
					tagName="div"
					className="section-label"
					value={sectionLabel}
					onChange={(val) => setAttributes({ sectionLabel: val })}
					placeholder={__('Label...', 'philosophy-block')}
				/>
				<RichText
					tagName="p"
					className="phil-statement"
					value={philosophyStatement}
					onChange={(val) => setAttributes({ philosophyStatement: val })}
					placeholder={__('Statement...', 'philosophy-block')}
					multiline={false}
				/>
			</div>

			<div className="phil-right">
				<RichText
					tagName="p"
					className="phil-text"
					value={philosophyText1}
					onChange={(val) => setAttributes({ philosophyText1: val })}
					placeholder={__('Body text...', 'philosophy-block')}
				/>
				<RichText
					tagName="p"
					className="phil-text"
					value={philosophyText2}
					onChange={(val) => setAttributes({ philosophyText2: val })}
					placeholder={__('Secondary text...', 'philosophy-block')}
				/>

				<div className="phil-divider"></div>

				<div className="phil-stats">
					{stats.map((stat, index) => (
						<div key={index} className="phil-stat-item">
							<RichText
								tagName="div"
								className="phil-stat-num"
								value={stat.num}
								onChange={(val) => updateStat(index, 'num', val)}
								placeholder="0"
							/>
							<RichText
								tagName="div"
								className="phil-stat-label"
								value={stat.label}
								onChange={(val) => updateStat(index, 'label', val)}
								placeholder="Label"
							/>
						</div>
					))}
				</div>
			</div>
		</section>
	);
}