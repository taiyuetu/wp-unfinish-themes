import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import './editor.scss';

/**
 * Card SVG icons – static, one per position.
 */
const CARD_ICONS = [
	// 01 – sun / precision
	<svg key="icon-0" className="icon icon--lg" viewBox="0 0 24 24" aria-hidden="true">
		<circle cx="12" cy="12" r="3" />
		<path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83" />
	</svg>,
	// 02 – document lines
	<svg key="icon-1" className="icon icon--lg" viewBox="0 0 24 24" aria-hidden="true">
		<rect x="2" y="2" width="20" height="20" rx="2" />
		<path d="M7 7h10M7 12h10M7 17h6" />
	</svg>,
	// 03 – clock / heat
	<svg key="icon-2" className="icon icon--lg" viewBox="0 0 24 24" aria-hidden="true">
		<path d="M12 2a10 10 0 100 20A10 10 0 0012 2z" />
		<path d="M12 6v6l4 2" />
	</svg>,
	// 04 – waveform / assembly
	<svg key="icon-3" className="icon icon--lg" viewBox="0 0 24 24" aria-hidden="true">
		<polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
	</svg>,
];

/**
 * Single capability card – fully inline-editable.
 */
function CapabilityCard( { card, index, onChange, onRemove } ) {
	const update = ( key ) => ( value ) =>
		onChange( index, { ...card, [ key ]: value } );

	return (
		<div className="capability-card">
			<div className="capability-icon">
				{ CARD_ICONS[ index % CARD_ICONS.length ] }
			</div>

			<RichText
				tagName="div"
				className="capability-card__number"
				value={ card.number }
				onChange={ update( 'number' ) }
				placeholder={ __( '01', 'axiom-capabilities' ) }
				allowedFormats={ [] }
			/>

			<RichText
				tagName="div"
				className="capability-card__title"
				value={ card.title }
				onChange={ update( 'title' ) }
				placeholder={ __( 'Card Title', 'axiom-capabilities' ) }
				allowedFormats={ [] }
			/>

			<RichText
				tagName="p"
				className="capability-card__text"
				value={ card.text }
				onChange={ update( 'text' ) }
				placeholder={ __( 'Card description…', 'axiom-capabilities' ) }
				allowedFormats={ [ 'core/bold', 'core/italic' ] }
			/>

			<div className="capability-card__metric-row">
				<RichText
					tagName="span"
					className="capability-card__metric-value"
					value={ card.metric }
					onChange={ update( 'metric' ) }
					placeholder={ __( '100', 'axiom-capabilities' ) }
					allowedFormats={ [] }
				/>
				<RichText
					tagName="span"
					className="capability-card__metric-suffix"
					value={ card.metricSuffix }
					onChange={ update( 'metricSuffix' ) }
					placeholder={ __( '%', 'axiom-capabilities' ) }
					allowedFormats={ [] }
				/>
			</div>

			<RichText
				tagName="div"
				className="capability-card__metric-label"
				value={ card.metricLabel }
				onChange={ update( 'metricLabel' ) }
				placeholder={ __( 'Metric Label', 'axiom-capabilities' ) }
				allowedFormats={ [] }
			/>

			{ /* Remove card button – visible only in editor */ }
			<button
				type="button"
				className="axiom-card-remove"
				onClick={ () => onRemove( index ) }
				aria-label={ __( 'Remove card', 'axiom-capabilities' ) }
			>
				✕
			</button>
		</div>
	);
}


export default function Edit( { attributes, setAttributes } ) {
	const { tag, sectionTitle, intro, cards } = attributes;

	const blockProps = useBlockProps( {
		className: 'capabilities',
	} );

	/* ── card helpers ── */
	const updateCard = ( index, updated ) => {
		const next = [ ...cards ];
		next[ index ] = updated;
		setAttributes( { cards: next } );
	};

	const removeCard = ( index ) => {
		setAttributes( { cards: cards.filter( ( _, i ) => i !== index ) } );
	};

	const addCard = () => {
		setAttributes( {
			cards: [
				...cards,
				{
					number: String( cards.length + 1 ).padStart( 2, '0' ),
					title: __( 'New Capability', 'axiom-capabilities' ),
					text: __( 'Describe this capability…', 'axiom-capabilities' ),
					metric: '0',
					metricSuffix: '',
					metricLabel: __( 'Metric Label', 'axiom-capabilities' ),
				},
			],
		} );
	};

	return (
		<section { ...blockProps }>
			<div className="container">

				{ /* ── Header ── */ }
				<div className="capabilities__header">
					<div>
						<RichText
							tagName="div"
							className="tag"
							value={ tag }
							onChange={ ( value ) => setAttributes( { tag: value } ) }
							placeholder={ __( 'Tag line', 'axiom-capabilities' ) }
							allowedFormats={ [] }
						/>

						<RichText
							tagName="h2"
							className="section-title"
							value={ sectionTitle }
							onChange={ ( value ) =>
								setAttributes( { sectionTitle: value } )
							}
							placeholder={ __( 'Section title…', 'axiom-capabilities' ) }
							allowedFormats={ [ 'core/bold', 'core/italic' ] }
						/>
					</div>

					<RichText
						tagName="p"
						className="capabilities__intro"
						value={ intro }
						onChange={ ( value ) => setAttributes( { intro: value } ) }
						placeholder={ __( 'Intro paragraph…', 'axiom-capabilities' ) }
						allowedFormats={ [ 'core/bold', 'core/italic' ] }
					/>
				</div>

				{ /* ── Cards grid ── */ }
				<div className="capabilities__grid">
					{ cards.map( ( card, index ) => (
						<CapabilityCard
							key={ index }
							card={ card }
							index={ index }
							onChange={ updateCard }
							onRemove={ removeCard }
						/>
					) ) }

					{ /* Add card button – editor only */ }
					<button
						type="button"
						className="axiom-add-card"
						onClick={ addCard }
					>
						<span>+</span>
						{ __( 'Add Capability Card', 'axiom-capabilities' ) }
					</button>
				</div>

			</div>
		</section>
	);
}