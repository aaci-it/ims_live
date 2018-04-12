<script type="text/javascript">

    $(document).ready(function () {

        $('#StudentTableContainer').jtable({
            title: 'The Student List',
            paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'Name ASC', //Set default sorting
            actions: {
                listAction: '/Demo/StudentList',
                deleteAction: '/Demo/DeleteStudent',
                updateAction: '/Demo/UpdateStudent',
                createAction: '/Demo/CreateStudent'
            },
            fields: {
                StudentId: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
                Name: {
                    title: 'Name',
                    width: '23%'
                },
                EmailAddress: {
                    title: 'Email address',
                    list: false
                },
                Password: {
                    title: 'User Password',
                    type: 'password',
                    list: false
                },
                Gender: {
                    title: 'Gender',
                    width: '13%',
                    options: { 'M': 'Male', 'F': 'Female' }
                },
                CityId: {
                    title: 'City',
                    width: '12%',
                    options: '/Demo/GetCityOptions'
                },
                BirthDate: {
                    title: 'Birth date',
                    width: '15%',
                    type: 'date',
                    displayFormat: 'yy-mm-dd'
                },
                Education: {
                    title: 'Education',
                    list: false,
                    type: 'radiobutton',
                    options: { '1': 'Primary school', 
                               '2': 'High school', 
                               '3': 'University' }
                },
                About: {
                    title: 'About this person',
                    type: 'textarea',
                    list: false
                },
                IsActive: {
                    title: 'Status',
                    width: '12%',
                    type: 'checkbox',
                    values: { 'false': 'Passive', 'true': 'Active' },
                    defaultValue: 'true'
                },
                RecordDate: {
                    title: 'Record date',
                    width: '15%',
                    type: 'date',
                    displayFormat: 'dd.mm.yy',
                    create: false,
                    edit: false,
                    sorting: false //This column is not sortable!
                }
            }
        });

        //Load student list from server
        $('#StudentTableContainer').jtable('load');
    });

</script>