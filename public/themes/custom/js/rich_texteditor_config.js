function elemen_rich_text_editor(element) {
    $(element).richText({
       // text formatting
        bold: true,
        italic: true,
        underline: true,
        // text alignment
        leftAlign: false,
        centerAlign: false,
        rightAlign: false,
        justify: false,
        // lists
        ol: true,
        ul: true,
        // title
        heading: false,

        // fonts
        fonts: false,
       
        fontColor: false,
        fontSize: false,

        // uploads
        imageUpload: false,
        fileUpload: false,

        // media
        videoEmbed: false,

        // link
        urls: false,

        // tables
        table: false,

        // code
        removeStyles: false,
        code: false,

        // dropdowns
        fileHTML: '',
        imageHTML: '',

        // privacy
        youtubeCookies: false,
        
        // developer settings
        useSingleQuotes: false,
        height: 0,
        heightPercentage: 0,
        id: "",
        class: "",
        useParagraph: false,
        maxlength: 0
      }
    );
}

