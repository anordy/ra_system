# Workflow Management.
The workflow engine was customized from existing package called `symfony/workflow` which provides tools for managing a workflow or finite state machine. Also the design and development uses alot of array configuration on the source code while on the database the information are stored as the json.

## Artifacts
The workflow artifact are found in
- **app/Services/Workflow** directory contains all the core implementation of the workflow engine
- **database** directory contains the 3 migration file required by the workflow engine
- **app/Traits** contains different traits file to handle manipulation of the workflow engine
- **app/Models** contains model classes required by the workflow engine

Below are list of the folders available in the **app/Services/Workflow** core folders
- Event - This folder contains lists of all classes that represent events executed within the workflow approval processing.
- Exception - This folder contains lists of all the exception classes that are thrown during the workflow approval process
- MarkingStore - This folder contains logic for handling the custom field defined in the table  used for tracking the approval process different stages.
- Subscriber - This folder contains classes for workflow subscriber which handles all the events that will be emited within the workflow approval process.
- SupportStrategy - This folder contain classes for handling support for the workflow
- Validator - This folder contains different classes for validation.


## Configuring a Workflow.
All configuration of the workflow are stored on tables below
- `workflow` - This table contains all the required field for defining a workflow.
- `workflow_tasks` - This table contains all the worklow stages old/active where there will always be one active stage. Table handle all the to and from stages of the worklow 
- `workflow_task_operators` - This table contais all opererators who will be acting on the given stage of the workflow but only one of the can approve the request.

Workflow engine utilization
- Every model that that require a workflow will require  **App\Traits\WorkflowTrait** trait which contains predefined methods and relationships required by the engine. 
- The **getMarking** and **setMarking** are method used to configure current stage of the workflow on the respective table. Hence the given table should have the extra column for this with the name **marking** for the engine to work properly.
- For generic approval process you can utilize the  **App\Traits\WorkflowProcesssingTrait** trait to perform different action on the workflow such as doing transtion from one stage to another, getting enable transition.

NB For the custom workflow processing we have implemented different workflow processing traits by through customization the original  **App\Traits\WorkflowProcesssingTrait** so as to have the controll on the workflow actions and movements.

Description of `workflow` table
```php
    Schema::create('workflows', function (Blueprint $table) {
        $table->string('code')->unique();
        $table->string('name');
        $table->string('type');
        $table->string('marking_store');
        $table->string('initial_marking');
        $table->string('last_marking')->nullable();
        $table->string('supports');
        $table->string('places', 4000);
        $table->string('transitions', 4000);
        $table->string('summary');
        $table->boolean('active')->default(1);
    });
```
Fields descriptions
- `code` field used to differentiate between one flow from another
- `name` field used to the name that will be displayed during the workflow execution process.
- `type` field used to determine if the process is either workflow or state machine but currently the supported type is workflow.
- `marking_store` field determine which fields/column name from the respective table used for approval that will be used to keep track of approval progress. This field accept json eg. *{"type":"multiple_state","property":["marking"]}*
- `initial_marking` field used to determine the inital place of the workflow.
- `last_marking` field used to determine the last place of the workflow.
- `supports` field used to determine which model participate on the workflow process.
- `places` field used to determine the stage/step in the process. This field accept json eg. *{"apply":{"owner":"taxpayer","operator_type":"user","operators":[]}}*
- `transitions` field used to determine the action need to get from on place to another. eg. *{"application_submitted":{"from":"apply","to":"audit_manager","condition":""}}*
- `summary` field used to write the workflow description
- `active` field used to determine the status of the flow either active or inactive.


Description of `workflow_tasks` table
```php
    Schema::create('workflow_tasks', function (Blueprint $table) {
        $table->string('pinstance_type');
        $table->unsignedBigInteger('pinstance_id');
        $table->unsignedBigInteger('workflow_id');
        $table->string('name');
        $table->string('from_place');
        $table->string('to_place');
        $table->enum('owner', ['staff', 'system', 'taxpayer']);
        $table->string('operator_type');
        $table->string('operators');
        $table->timestamp('approved_on')->nullable();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->string('user_type')->nullable();
        $table->enum('status', $status_choices)->default('running');
        $table->string('remarks')->nullable();
    });
```

Fields descriptions
- `pinstance_id` field used to determine the owner id of the workflow process
- `pinstance_type` field used to determine the model name of the worklow owner eg *App\Models\TaxAgent*
- `workflow_id` field used to determine the id of the workflow configuration used for this approval process
- `name` field used used to determine the name of the stage
- `from_place` field used to determine the stage where the process is from.
- `to_place` field used to determine the stage where the process is going to.
- `owner` field used to determine who will be the executing or owning the stage
- `operator_type` field used to determine the type of the actors required to complete the stage eg. user, roles
- `operators` field used to list of the operators who can act or approve the stage and it's store as the json. eg. [1,2,3,4]
- `approved_on` field used to determine when the request was approved
- `user_id` field used used to determine which user approved the request
- `user_type` field used to determine the model name of the user that has appproved the request
- `status` field used to determine the different status of the approval such as running, completed, rejecte
- `remarks` field used to store the comments during the approval process.

Description of `workflow_task_operators` table
```php
    Schema::create('workflow_task_operators', function (Blueprint $table) {
        $table->unsignedBigInteger('task_id');
        $table->unsignedBigInteger('workflow_id');
        $table->unsignedBigInteger('user_id');
        $table->string('user_type');
    });
```
Fields descriptions
- `task_id` field show the relationship between the workflow_task_operators and workflow_tasks where by one task can have many operators
- `workflow_id` field used to determine the which workflow are the operators acting.
- `user_id` field used to determine id of the user
- `user_type` field used to determine the type of the user especial the model namespace eg. *App\Models\User*


## Creating Workflow
Creation of the workflow currently is done manually where by you can create the seeder with all the configuration required for the workflow for the reference you can check currently implement workflow in the system.

## Updating Workflow
Currently updating the workflow is scoped only on the operators changes during the workflow configuration and user role changes from the UI. 
To accomodate this we have implement jobs to help with updation for the details of how the job work you can look into below listed classes
- App\Jobs\Workflow\UserUpdateActors
- App\Jobs\Workflow\WorkflowUpdateActors

## Running Workflow
In order to run the workflow you need to do the following
- Require the *WorkflowProcesssingTrait* or *your custom trait* to the place where the action is trigger.
- Register the workflow using the method *registerWorkflow* so as the engine will get the configuration for the given workflow.
- After the registration you can perform below action
    - Do transition from one place to another using the function *doTransition($transtion, $context)*
    - Check if the current transition is equal to the given transition name *checkTransition($transition_name)*
    - Get all the enable transtion using the *getEnabledTransitions()*
- For custom action on the approval process you need to add the logics on *WorkflowSubscriber* class as currently all the notification and email notification of the approval are manually added in the class.

### WorkflowSubscriber class explanation
This class contains events that are triggered during the approval process which come in hand when you need to implement different custom logic or you need to have granular control on the workflow process.
```php
class WorkflowSubscriber implements EventSubscriberInterface
{
    protected $expressionLanguage;

    public function __construct()
    {
        $this->expressionLanguage = new ExpressionLanguage();
    }
    public function guardEvent(GuardEvent $event)
    {
        // Handle logics to controll and block stages with accordance to the operators of the process
    }
     public function completedEvent(Event $event)
     {
        // Handle logics to advance to next stage and mark the old stage as completed
     }

    public function announceEvent(Event $event)
    {
        // Handle logics for notification after transition is complted
    }
}

```
Show below are most imporant methods for the operation of the worflow engine
- guardEvent - This method/event is used to validate if the user has the access to peform action in the given workflow process. So, here is where we block access for other users who have no access to the process
- completedEvent - This method/event is executed when the engine want to completed transtion from one stage to another, 
    - Define new task in the database and marking the previous one as completed.
    - Define and send SMS/Email notification to the next operators
- announceEvent - This method/event is executed when the workflow process has completed transition and here is where create database notification which user will view them on the system.