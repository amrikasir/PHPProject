<?php
/**
 * This file is part of PHPProject - A pure PHP library for reading and writing
 * presentations documents.
 *
 * PHPProject is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPProject
 * @copyright   2009-2014 PHPProject contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpProject;

/**
 * PHPProject_Task
 *
 * @category	PHPProject
 * @package		PHPProject
 * @copyright	Copyright (c) 2012 - 2012 PHPProject (https://github.com/PHPOffice/PHPProject)
 */
class Task
{
	/**
	 * Name
	 * 
	 * @var string
	 */
	private $_name;
	
	/**
	 * Duration
	 * 
	 * @var string
	 */
	private $_duration;
	
	/**
	 * Start Date
	 *
	 * @var	datetime
	 */
	private $_startDate;
	
	/**
	 * End Date
	 *
	 * @var	datetime
	 */
	private $_endDate;
	
	/**
	 * Progress
	 *
	 * @var	float
	 */
	private $_progress;
	
	/**
	 * Parent Project
	 * 
	 * @var PHPProject
	 */
	private $_parentProject;
	
	/**
	 * Parent Task
	 *
	 * @var PHPProject_Task
	 */
	private $_parentTask;
	
	/**
	 * Index
	 *
	 * @var integer
	 */
	private $_index;
	
	/**
	 * Collection of PHPProject_Resource index
	 * 
	 * @var integer[]
	 */
	private $_resourceCollection;
	
	/**
	 * Collection of task objects
	 *
	 * @var PHPProject_Task[]
	 */
	private $_taskCollection = array();
	
	/**
	 * Active task
	 *
	 * @var int
	 */
	private $_activeTaskIndex = 0;
	
	public function __construct(PhpProject $pParent, $pIndex, Task $pParentTask = null){
		$this->_parentProject = $pParent;
		$this->_parentTask = $pParentTask;
		$this->_index = $pIndex;
		
		$this->_resourceCollection = array();
	}
	
	/**
	 * Get parent
	 *
	 * @return PHPProject
	 */
	public function getParent() {
		return $this->_parent;
	}

	/**
	 * Get parent task
	 *
	 * @return PHPProject_Task
	 */
	public function getParentTask() {
		return $this->_parentTask;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}
	
	/**
	 * Set name
	 *
	 * @param string $pValue Name of the task
	 * @return PHPProject_Task
	 */
	public function setName($pValue)
	{
		$this->_name = $pValue;
		return $this;
	}
	
	/**
	 * Get duration
	 *
	 * @return string
	 */
	public function getDuration()
	{
		return $this->_duration;
	}
	
	/**
	 * Set duration
	 *
	 * @param string $pValue Duration of the resource
	 * @return PHPProject_Task
	 */
	public function setDuration($pValue)
	{
		$this->_duration = $pValue;
		return $this;
	}
	
	/**
	 * Get Start Date
	 *
	 * @return	datetime
	 */
	public function getStartDate() {
		return $this->_startDate;
	}

	/**
	 * Set Start Date
	 *
	 * @param	datetime	$pValue
	 * @return	PHPProject_DocumentInformations
	 */
	public function setStartDate($pValue = null) {
		if ($pValue === NULL) {
			$pValue = time();
		} elseif (is_string($pValue)) {
			if (is_numeric($pValue)) {
				$pValue = intval($pValue);
			} else {
				$pValue = strtotime($pValue);
			}
		}

		$this->_startDate = $pValue;
		return $this;
	}

	/**
	 * Get End Date
	 *
	 * @return	datetime
	 */
	public function getEndDate() {
		return $this->_endDate;
	}

	/**
	 * Set End Date
	 *
	 * @param	datetime	$pValue
	 * @return	PHPProject_DocumentInformations
	 */
	public function setEndDate($pValue = null) {
		if ($pValue === NULL) {
			$pValue = time();
		} elseif (is_string($pValue)) {
			if (is_numeric($pValue)) {
				$pValue = intval($pValue);
			} else {
				$pValue = strtotime($pValue);
			}
		}

		$this->_endDate = $pValue;
		return $this;
	}
	
	/**
	 * Get Progress
	 *
	 * @return float
	 */
	public function getProgress()
	{
		return $this->_progress;
	}
	
	/**
	 * Set progress
	 *
	 * @param float $pValue Progress of the task
	 * @return PHPProject_Task
	 */
	public function setProgress($pValue = 0)
	{
		if($pValue > 1){
			$this->_progress = 1;
		} elseif($pValue < 0){
			$this->_progress = 0;
		} else {
			$this->_progress = $pValue;
		}
		return $this;
	}
	
	//===============================================
	// Resources
	//===============================================
	/**
	 * Add a resource used by the current task
	 * @param PHPProject_Resource $pResource
	 */
	public function addResource(Resource $pResource){
		if(array_search($pResource->getIndex(), $this->_resourceCollection) === false){
			$this->_resourceCollection[] = $pResource->getIndex();
		}
		return $this;
	}

	/**
	 * Returns a collection of all resources used by the task
	 * 
	 * @return integer[]
	 */
	public function getResources(){
		return $this->_resourceCollection;
	}

	public function getResourceCount(){
		return count($this->_resourceCollection);
	}
	
	//===============================================
	// Tasks
	//===============================================
	public function createTask(){
		$newTask = new self($this->_parentProject, $this->getTaskCount(), $this);
		$this->_taskCollection[] = $newTask;
		$this->_activeTaskIndex = $this->getTaskCount() - 1;
		return $newTask;
	}
	
	/**
	 * Returns a collection of all subtasks created in the task
	 *
	 * @return PHPProject_Task[]
	 */
	public function getTasks()
	{
		return $this->_taskCollection;
	}

	public function getTaskCount(){
		return count($this->_taskCollection);
	}
}