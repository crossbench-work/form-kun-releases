ALTER TABLE form_fields MODIFY COLUMN type ENUM(
    'text','textarea','email','tel','number','address','text_pair',
    'radio','checkbox','select','date','time','file'
) NOT NULL;
