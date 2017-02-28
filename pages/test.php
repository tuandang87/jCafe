<script type="text/ng-template" id="customTemplate.html">
  <a>
     <!-- <img ng-src="http://upload.wikimedia.org/wikipedia/commons/thumb/{{match.model.flag}}" width="16"> -->
      <span ng-bind-html="match.label | uibTypeaheadHighlight:query"></span>
  </a>
</script>
<div ng-controller="testController">
	{{Message}}
	
	<h4>Custom templates for results</h4>
    <pre>Model: {{customSelected | json}}</pre>
    <input type="text" ng-model="customSelected" placeholder="Custom template" 
    	uib-typeahead="state as state.name for state in statesWithFlags | filter:{name:$viewValue}" 
    	class="form-control" typeahead-show-hint="true" 
    	typeahead-min-length="0">
</div>
