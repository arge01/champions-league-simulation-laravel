<?php

namespace App\Http\Controllers;

use App\Models\FIN;
use App\Models\QUA;
use App\Models\QUARTER;
use App\Models\SEMI;
use App\Models\Tournamed;
use Illuminate\Http\Request;

class SimulationNextMatchController extends Controller
{
	private $stayed = "qua";

	private $data;

	private $tournamed;

	private $qua;
	private $quarter;
	private $semi;
	private $final;

	public function __construct(Request $request, Tournamed $tournamed)
	{
		$this->data = $request->json()->all();
		$this->tournamed = $tournamed->first();

		$this->qua = QUA::where('tournamed', $this->tournamed->id)
			->with(['field', 'outfield', 'tournamed'])
			->with('field.power')
			->with('outfield.power');

		$this->quarter = QUARTER::where('tournamed', $this->tournamed->id)
			->with(['field', 'outfield', 'tournamed'])
			->with('field.power')
			->with('outfield.power');

		$this->semi = SEMI::where('tournamed', $this->tournamed->id)
			->with(['field', 'outfield', 'tournamed'])
			->with('field.power')
			->with('outfield.power');

		$this->final = FIN::where('tournamed', $this->tournamed->id)
			->with(['field', 'outfield', 'tournamed'])
			->with('field.power')
			->with('outfield.power');
	}

	private function checkStageExists($model, $tournamedId)
	{
		return $model::where('tournamed', $tournamedId)->exists();
	}

	private function saveNeeded($model, $data)
	{
		ini_set('max_execution_time', 3600);

		foreach ($data as $key => $value) {
			$match = [
				"field" => $value["field"]["id"],
				"goal_field" => $value["goalField"],
				"goal_outfield" => $value["goalOutField"],
				"outfield" => $value["outfield"]["id"],
				"tournamed" => $value["tournamed"]["id"],
				"goal_outfield_penalty" =>
				isset($value["goalOutFieldPenalty"]) ? $value["goalOutFieldPenalty"] : null,
				"goal_field_penalty" =>
				isset($value["goalFieldPenalty"]) ? $value["goalFieldPenalty"] : null,
				"is_penalty" => $value["isPenalty"] ? 1 : 0,
			];

			try {
				$model::create($match);
			} catch (\Exception $e) {
				return response()->json(['error' => $e->getMessage()], 500);
			}
		}

		$this->stayed = $this->stayedNeeded($this->tournamed->id);
	}

	private function showNeeded($tournamed)
	{
		$this->tournamed = $this->tournamed->where("id", $tournamed)->first();
		$data = [
			"qua" => $this->qua->where("tournamed", $this->tournamed->id)->get(),
			"quarter" => $this->quarter->where("tournamed", $this->tournamed->id)->get(),
			"semi" => $this->semi->where("tournamed", $this->tournamed->id)->get(),
			"final" => $this->final->where("tournamed", $this->tournamed->id)->get(),
			"champion" => $this->final->where("tournamed", $this->tournamed->id)->get(),
		];

		if (!$this->tournamed) {
			return response()->json(["error" => "Tournament not found"], 404);
		}

		return $data;
	}

	private function stayedNeeded($tournamed)
	{
		$this->tournamed = $this->tournamed->where("id", $tournamed)->first();

		if (!$this->tournamed) {
			$this->stayed = "";
			return response()->json(["error" => "Tournament not found"], 404);
		}

		if ($this->checkStageExists(QUA::class, $this->tournamed->id)) {
			$this->stayed = "quarter";
		}

		if ($this->checkStageExists(QUARTER::class, $this->tournamed->id)) {
			$this->stayed = "semi";
		}

		if ($this->checkStageExists(SEMI::class, $this->tournamed->id)) {
			$this->stayed = "final";
		}

		if ($this->checkStageExists(FIN::class, $this->tournamed->id)) {
			$this->stayed = "champion";
		}

		return $this->stayed;
	}

	public function get($tournamed)
	{
		$this->tournamed = $this->tournamed->where("id", $tournamed)->first();

		$this->stayed = $this->stayedNeeded($tournamed);

		return response()->json(
			[
				"data" => $this->showNeeded($tournamed),
				"name" => $this->stayed,
			]
		);
	}

	public function create($tournamed, $stayed)
	{
		ini_set('max_execution_time', 3600);

		$this->tournamed = $this->tournamed->where("id", $tournamed)->first();

		$this->stayed = $this->stayedNeeded($tournamed);

		if ($stayed === "qua") {
			$this->saveNeeded(QUA::class, $this->data);
		}

		if ($stayed === "quarter") {
			$this->saveNeeded(QUARTER::class, $this->data);
		}

		if ($stayed === "semi") {
			$this->saveNeeded(SEMI::class, $this->data);
		}

		if ($stayed === "final") {
			$this->saveNeeded(FIN::class, $this->data);
		}

		return response()->json(
			[
				"data" => $this->showNeeded($tournamed),
				"name" => $this->stayed,
			]
		);
	}
}
