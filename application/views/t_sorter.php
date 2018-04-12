<!DOCTYPE HTML>

<html lang="en">

	<head>
		<!-- jQuery -->
		<script src="<?php echo base_url();?>tablesorter-master/js/jquery-3.0.0.min.js"></script>

		<!-- Demo stuff -->
		<link rel="stylesheet" href="<?php echo base_url();?>tablesorter-master/docs/css/jq.css">
		<link rel="stylesheet" href="<?php echo base_url();?>tablesorter-master/docs/css/bootstrap-v2.min.css">
		<link href="<?php echo base_url();?>tablesorter-master/docs/css/prettify.css" rel="stylesheet">
		<script src="<?php echo base_url();?>tablesorter-master/docs/js/prettify.js"></script>
		<script src="<?php echo base_url();?>tablesorter-master/docs/js/docs.js"></script>

		<!-- Tablesorter: required for bootstrap -->
		<link rel="stylesheet" href="<?php echo base_url();?>tablesorter-master/css/theme.bootstrap_2.css">
		<script src="<?php echo base_url();?>tablesorter-master/js/jquery.tablesorter.js"></script>
		<script src="<?php echo base_url();?>tablesorter-master/js/jquery.tablesorter.widgets.js"></script>

		<!-- Tablesorter: optional -->
		<link rel="stylesheet" href="<?php echo base_url();?>tablesorter-master/addons/pager/jquery.tablesorter.pager.css">
		<script src="<?php echo base_url();?>tablesorter-master/addons/pager/jquery.tablesorter.pager.js"></script>

		<script id="js">$(function() {

		// NOTE: $.tablesorter.theme.bootstrap is ALREADY INCLUDED in the jquery.tablesorter.widgets.js
		// file for Bootstrap v3.x; it is included here to show how you can modify the default classes
		// for version 2.x (the iconSortAsc & iconSortDesc use different classes)
		$.tablesorter.themes.bootstrap = {
			// these classes are added to the table. To see other table classes available,
			// look here: http://getbootstrap.com/css/#tables
			table        : 'table table-bordered table-striped',
			caption      : 'caption',
			// header class names
			header       : 'bootstrap-header', // give the header a gradient background (theme.bootstrap_2.css)
			sortNone     : '',
			sortAsc      : '',
			sortDesc     : '',
			active       : '', // applied when column is sorted
			hover        : '', // custom css required - a defined bootstrap style may not override other classes
			// icon class names
			icons        : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
			iconSortNone : 'bootstrap-icon-unsorted', // class name added to icon when column is not sorted
			iconSortAsc  : 'icon-chevron-up', // class name added to icon when column has ascending sort
			iconSortDesc : 'icon-chevron-down', // class name added to icon when column has descending sort
			filterRow    : '', // filter row class
			footerRow    : '',
			footerCells  : '',
			even         : '', // even row zebra striping
			odd          : ''  // odd row zebra striping
		};

		// call the tablesorter plugin and apply the uitheme widget
		$("table").tablesorter({
			// this will apply the bootstrap theme if "uitheme" widget is included
			// the widgetOptions.uitheme is no longer required to be set
			theme : "bootstrap",

			widthFixed: true,

			headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!

			// widget code contained in the jquery.tablesorter.widgets.js file
			// use the zebra stripe widget if you plan on hiding any rows (filter widget)
			widgets : [ "uitheme", "filter", "zebra" ],

			widgetOptions : {
				// using the default zebra striping class name, so it actually isn't included in the theme variable above
				// this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
				zebra : ["even", "odd"],

				// reset filters button
				filter_reset : ".reset",

				// hide the filter row when not active
				filter_hideFilters : true

			}
		})
		.tablesorterPager({

			// target the pager markup - see the HTML block below
			container: $(".ts-pager"),

			// target the pager page select dropdown - choose a page
			cssGoto  : ".pagenum",

			// remove rows from the table to speed up the sort of large tables.
			// setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
			removeRows: false,

			// output string - default is '{page}/{totalPages}';
			// possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
			output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'

		});

	});</script>

		<script>
		$(function(){

			// filter button demo code
			$('button.filter').click(function(){
				var col = $(this).data('column'),
					txt = $(this).data('filter');
				$('table').find('.tablesorter-filter').val('').eq(col).val(txt);
				$('table').trigger('search', false);
				return false;
			});

			// toggle zebra widget
			$('button.zebra').click(function(){
				var t = $(this).hasClass('btn-success');
	//			if (t) {
					// removing classes applied by the zebra widget
					// you shouldn't ever need to use this code, it is only for this demo
	//				$('table').find('tr').removeClass('odd even');
	//			}
				$('table')
					.toggleClass('table-striped')[0]
					.config.widgets = (t) ? ["uitheme", "filter"] : ["uitheme", "filter", "zebra"];
				$(this)
					.toggleClass('btn-danger btn-success')
					.find('i')
					.toggleClass('icon-ok icon-remove glyphicon-ok glyphicon-remove').end()
					.find('span')
					.text(t ? 'disabled' : 'enabled');
				$('table').trigger('refreshWidgets', [false]);
				return false;
			});
		});
		</script>


	</head>

	<body>

<div id="main">

	
	<table> <!-- bootstrap classes added by the uitheme widget -->
	<thead>
		<tr>
			<th>Name</th>
			<th>Major</th>
			<th class="filter-select filter-exact" data-placeholder="Pick a gender">Sex</th>
			<th>English</th>
			<th>Japanese</th>
			<th>Calculus</th>
			<th>Geometry</th></tr>
	</thead>
	<tfoot>
		<tr>
			<th>Name</th>
			<th>Major</th>
			<th>Sex</th>
			<th>English</th>
			<th>Japanese</th>
			<th>Calculus</th>
			<th>Geometry</th>
		</tr>
		<tr>
			<th colspan="7" class="ts-pager form-horizontal">
				<button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
				<button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
				<span class="pagedisplay"></span> <!-- this can be any element, including an input -->
				<button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
				<button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
				<select class="pagesize input-mini" title="Select page size">
					<option selected="selected" value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
				</select>
				<select class="pagenum input-mini" title="Select page number"></select>
			</th>
		</tr>
	</tfoot>
	<tbody>
		<tr><td>Student01</td><td>Languages</td><td>male</td><td>80</td><td>70</td><td>75</td><td>80</td></tr>
		<tr><td>Student02</td><td>Mathematics</td><td>male</td><td>90</td><td>88</td><td>100</td><td>90</td></tr>
		<tr><td>Student03</td><td>Languages</td><td>female</td><td>85</td><td>95</td><td>80</td><td>85</td></tr>
		<tr><td>Student04</td><td>Languages</td><td>male</td><td>60</td><td>55</td><td>100</td><td>100</td></tr>
		<tr><td>Student05</td><td>Languages</td><td>female</td><td>68</td><td>80</td><td>95</td><td>80</td></tr>
		<tr><td>Student06</td><td>Mathematics</td><td>male</td><td>100</td><td>99</td><td>100</td><td>90</td></tr>
		<tr><td>Student07</td><td>Mathematics</td><td>male</td><td>85</td><td>68</td><td>90</td><td>90</td></tr>
		<tr><td>Student08</td><td>Languages</td><td>male</td><td>100</td><td>90</td><td>90</td><td>85</td></tr>
		<tr><td>Student09</td><td>Mathematics</td><td>male</td><td>80</td><td>50</td><td>65</td><td>75</td></tr>
		<tr><td>Student10</td><td>Languages</td><td>male</td><td>85</td><td>100</td><td>100</td><td>90</td></tr>
		<tr><td>Student11</td><td>Languages</td><td>male</td><td>86</td><td>85</td><td>100</td><td>100</td></tr>
		<tr><td>Student12</td><td>Mathematics</td><td>female</td><td>100</td><td>75</td><td>70</td><td>85</td></tr>
		<tr><td>Student13</td><td>Languages</td><td>female</td><td>100</td><td>80</td><td>100</td><td>90</td></tr>
		<tr><td>Student14</td><td>Languages</td><td>female</td><td>50</td><td>45</td><td>55</td><td>90</td></tr>
		<tr><td>Student15</td><td>Languages</td><td>male</td><td>95</td><td>35</td><td>100</td><td>90</td></tr>
		<tr><td>Student16</td><td>Languages</td><td>female</td><td>100</td><td>50</td><td>30</td><td>70</td></tr>
		<tr><td>Student17</td><td>Languages</td><td>female</td><td>80</td><td>100</td><td>55</td><td>65</td></tr>
		<tr><td>Student18</td><td>Mathematics</td><td>male</td><td>30</td><td>49</td><td>55</td><td>75</td></tr>
		<tr><td>Student19</td><td>Languages</td><td>male</td><td>68</td><td>90</td><td>88</td><td>70</td></tr>
		<tr><td>Student20</td><td>Mathematics</td><td>male</td><td>40</td><td>45</td><td>40</td><td>80</td></tr>
		<tr><td>Student21</td><td>Languages</td><td>male</td><td>50</td><td>45</td><td>100</td><td>100</td></tr>
		<tr><td>Student22</td><td>Mathematics</td><td>male</td><td>100</td><td>99</td><td>100</td><td>90</td></tr>
		<tr><td>Student23</td><td>Mathematics</td><td>male</td><td>82</td><td>77</td><td>0</td><td>79</td></tr>
		<tr><td>Student24</td><td>Languages</td><td>female</td><td>100</td><td>91</td><td>13</td><td>82</td></tr>
		<tr><td>Student25</td><td>Mathematics</td><td>male</td><td>22</td><td>96</td><td>82</td><td>53</td></tr>
		<tr><td>Student26</td><td>Languages</td><td>female</td><td>37</td><td>29</td><td>56</td><td>59</td></tr>
		<tr><td>Student27</td><td>Mathematics</td><td>male</td><td>86</td><td>82</td><td>69</td><td>23</td></tr>
		<tr><td>Student28</td><td>Languages</td><td>female</td><td>44</td><td>25</td><td>43</td><td>1</td></tr>
		<tr><td>Student29</td><td>Mathematics</td><td>male</td><td>77</td><td>47</td><td>22</td><td>38</td></tr>
		<tr><td>Student30</td><td>Languages</td><td>female</td><td>19</td><td>35</td><td>23</td><td>10</td></tr>
		<tr><td>Student31</td><td>Mathematics</td><td>male</td><td>90</td><td>27</td><td>17</td><td>50</td></tr>
		<tr><td>Student32</td><td>Languages</td><td>female</td><td>60</td><td>75</td><td>33</td><td>38</td></tr>
		<tr><td>Student33</td><td>Mathematics</td><td>male</td><td>4</td><td>31</td><td>37</td><td>15</td></tr>
		<tr><td>Student34</td><td>Languages</td><td>female</td><td>77</td><td>97</td><td>81</td><td>44</td></tr>
		<tr><td>Student35</td><td>Mathematics</td><td>male</td><td>5</td><td>81</td><td>51</td><td>95</td></tr>
		<tr><td>Student36</td><td>Languages</td><td>female</td><td>70</td><td>61</td><td>70</td><td>94</td></tr>
		<tr><td>Student37</td><td>Mathematics</td><td>male</td><td>60</td><td>3</td><td>61</td><td>84</td></tr>
		<tr><td>Student38</td><td>Languages</td><td>female</td><td>63</td><td>39</td><td>0</td><td>11</td></tr>
		<tr><td>Student39</td><td>Mathematics</td><td>male</td><td>50</td><td>46</td><td>32</td><td>38</td></tr>
		<tr><td>Student40</td><td>Languages</td><td>female</td><td>51</td><td>75</td><td>25</td><td>3</td></tr>
		<tr><td>Student41</td><td>Mathematics</td><td>male</td><td>43</td><td>34</td><td>28</td><td>78</td></tr>
		<tr><td>Student42</td><td>Languages</td><td>female</td><td>11</td><td>89</td><td>60</td><td>95</td></tr>
		<tr><td>Student43</td><td>Mathematics</td><td>male</td><td>48</td><td>92</td><td>18</td><td>88</td></tr>
		<tr><td>Student44</td><td>Languages</td><td>female</td><td>82</td><td>2</td><td>59</td><td>73</td></tr>
		<tr><td>Student45</td><td>Mathematics</td><td>male</td><td>91</td><td>73</td><td>37</td><td>39</td></tr>
		<tr><td>Student46</td><td>Languages</td><td>female</td><td>4</td><td>8</td><td>12</td><td>10</td></tr>
		<tr><td>Student47</td><td>Mathematics</td><td>male</td><td>89</td><td>10</td><td>6</td><td>11</td></tr>
		<tr><td>Student48</td><td>Languages</td><td>female</td><td>90</td><td>32</td><td>21</td><td>18</td></tr>
		<tr><td>Student49</td><td>Mathematics</td><td>male</td><td>42</td><td>49</td><td>49</td><td>72</td></tr>
		<tr><td>Student50</td><td>Languages</td><td>female</td><td>56</td><td>37</td><td>67</td><td>54</td></tr>
	</tbody>
</table>



</div>

</body>
</html>
