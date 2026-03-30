import {
	useBlockProps,
	RichText,
	MediaUpload,
	MediaUploadCheck,
	URLInput,
} from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
	const {
		tag,
		title,
		description,
		buttonText,
		buttonUrl,
		imageId,
		imageUrl,
		imageAlt,
		badgeValue,
		badgeLabel,
	} = attributes;

	const blockProps = useBlockProps( {
		className: 'about-hero',
	} );

	return (
		<section { ...blockProps }>
			<div className="container">
				<div className="about-hero__layout">

					{ /* ── LEFT COLUMN: content ── */ }
					<div className="about-hero__content">

						<RichText
							tagName="div"
							className="tag"
							value={ tag }
							onChange={ ( val ) => setAttributes( { tag: val } ) }
							placeholder={ __( 'Tag text…', 'about-hero' ) }
							allowedFormats={ [] }
						/>

						<RichText
							tagName="h2"
							className="about-hero__title"
							value={ title }
							onChange={ ( val ) => setAttributes( { title: val } ) }
							placeholder={ __( 'Heading…', 'about-hero' ) }
							allowedFormats={ [ 'core/bold', 'core/italic' ] }
						/>

						<RichText
							tagName="p"
							className="about-hero__desc"
							value={ description }
							onChange={ ( val ) => setAttributes( { description: val } ) }
							placeholder={ __( 'Description…', 'about-hero' ) }
							allowedFormats={ [ 'core/bold', 'core/italic' ] }
						/>

						<div className="about-hero__btn-wrap">
							<RichText
								tagName="span"
								className="btn-primary__text"
								value={ buttonText }
								onChange={ ( val ) => setAttributes( { buttonText: val } ) }
								placeholder={ __( 'Button label…', 'about-hero' ) }
								allowedFormats={ [] }
							/>
							<div className="about-hero__url-input">
								<URLInput
									label={ __( 'Button URL', 'about-hero' ) }
									value={ buttonUrl }
									onChange={ ( val ) => setAttributes( { buttonUrl: val } ) }
								/>
							</div>
						</div>
					</div>

					{ /* ── RIGHT COLUMN: visual ── */ }
					<div className="about-hero__visual">
						<MediaUploadCheck>
							<MediaUpload
								onSelect={ ( media ) =>
									setAttributes( {
										imageId: media.id,
										imageUrl: media.url,
										imageAlt: media.alt || '',
									} )
								}
								allowedTypes={ [ 'image' ] }
								value={ imageId }
								render={ ( { open } ) => (
									<div
										className="about-hero__image-wrap"
										onClick={ open }
										role="button"
										tabIndex={ 0 }
										onKeyDown={ ( e ) => e.key === 'Enter' && open() }
									>
										{ imageUrl ? (
											<img
												src={ imageUrl }
												alt={ imageAlt }
												className="about-hero__img"
											/>
										) : (
											<div className="about-hero__placeholder">
												<span>{ __( 'Click to upload image (512×400)', 'about-hero' ) }</span>
											</div>
										) }
										<div className="about-hero__image-overlay">
											<Button variant="secondary" onClick={ open }>
												{ imageUrl
													? __( 'Replace Image', 'about-hero' )
													: __( 'Upload Image', 'about-hero' ) }
											</Button>
										</div>
									</div>
								) }
							/>
						</MediaUploadCheck>

						<div className="about-hero__visual-badge">
							<RichText
								tagName="span"
								className="about-hero__visual-badge-value"
								value={ badgeValue }
								onChange={ ( val ) => setAttributes( { badgeValue: val } ) }
								placeholder={ __( '24+', 'about-hero' ) }
								allowedFormats={ [] }
							/>
							<RichText
								tagName="span"
								className="about-hero__visual-badge-label"
								value={ badgeLabel }
								onChange={ ( val ) => setAttributes( { badgeLabel: val } ) }
								placeholder={ __( 'Years of Excellence', 'about-hero' ) }
								allowedFormats={ [] }
							/>
						</div>
					</div>

				</div>
			</div>
		</section>
	);
}